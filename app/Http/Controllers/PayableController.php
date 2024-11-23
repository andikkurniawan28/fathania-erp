<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Payable;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PayableController extends Controller
{

    public function index(Request $request)
    {
        $setup = Setup::init();
        $suppliers = Supplier::all();
        return view('payable.index', compact('setup', 'suppliers'));
    }

    public function data(Request $request)
    {
        $fromDatetime = $request->start_date;
        $toDatetime = $request->end_date;
        $supplierId = $request->third_party_id;
        $yesterday = Carbon::parse($fromDatetime)->subDay()->format('Y-m-d');

        // Ambil data Payable sebelum periode yang diminta untuk saldo awal
        $initialBalanceQuery = Payable::where('supplier_id', $supplierId)
            ->where('created_at', '<', $fromDatetime)->sum('amount');

        // Ambil data Payable dalam periode yang diminta
        $data = Payable::with('supplier', 'user')
            ->where('supplier_id', $supplierId)
            ->whereBetween('created_at', [$fromDatetime, $toDatetime])
            ->orderBy('created_at', 'asc')
            ->get();

        $initialBalance = $initialBalanceQuery;
        $runningBalance = $initialBalance; // Mulai dengan saldo awal

        // Menyiapkan data termasuk saldo awal sebagai baris pertama
        $results = [];
        $results[] = [
            'created_at' => $yesterday,
            'description' => 'Saldo Awal',
            'amount' => $initialBalance,
            'balance' => $initialBalance,
            'user' => (object) ['name' => ''],
        ];

        foreach ($data as $row) {
            $runningBalance += $row->amount;
            $results[] = [
                'created_at' => $row->created_at->format('Y-m-d'),
                'description' => $row->description,
                'amount' => $row->amount,
                'balance' => $runningBalance,
                'user' => $row->user,
            ];
        }

        // Hitung saldo akhir berdasarkan normal_balance_id dari akun
        $finalBalance = $initialBalance;
        $finalBalance = $runningBalance;

        // Tambahkan saldo akhir sebagai baris terakhir
        $results[] = [
            'created_at' => $toDatetime,
            'description' => 'Saldo Akhir',
            'amount' => $finalBalance,
            'balance' => $finalBalance,
            'user' => (object) ['name' => ''],
        ];

        return Datatables::of(collect($results))
            ->addIndexColumn()
            ->make(true);
    }
}
