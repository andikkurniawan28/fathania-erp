<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\InventoryMovement;
use App\Models\Material;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoryMovementController extends Controller
{

    public function index(Request $request)
    {
        $setup = Setup::init();
        $materials = Material::all();
        $warehouses = Warehouse::all();
        return view('inventory_movement.index', compact('setup', 'materials', 'warehouses'));
    }

    public function data(Request $request)
    {
        $fromDatetime = $request->start_date;
        $toDatetime = $request->end_date;
        $materialId = $request->material_id;
        $warehouseId = $request->warehouse_id;
        $yesterday = Carbon::parse($fromDatetime)->subDay()->format('Y-m-d');

        // Ambil data InventoryMovement sebelum periode yang diminta untuk saldo awal
        $initialBalanceQuery = InventoryMovement::where('material_id', $materialId)
            ->where('warehouse_id', $warehouseId)
            ->where('created_at', '<', $fromDatetime);

        // Hitung total in dan out sebelum periode yang diminta
        $totalDebitBefore = $initialBalanceQuery->sum('in');
        $totalCreditBefore = $initialBalanceQuery->sum('out');

        // Ambil data InventoryMovement dalam periode yang diminta
        $data = InventoryMovement::with('material', 'user', 'warehouse')
            ->where('material_id', $materialId)
            ->where('warehouse_id', $warehouseId)
            ->whereBetween('created_at', [$fromDatetime, $toDatetime])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal berdasarkan normal_balance_id dari akun
        $initialBalance = 0;
        $initialBalance = ($totalDebitBefore - $totalCreditBefore);

        $runningBalance = $initialBalance; // Mulai dengan saldo awal

        // Menyiapkan data termasuk saldo awal sebagai baris pertama
        $results = [];
        $results[] = [
            'created_at' => $yesterday,
            'description' => 'Saldo Awal',
            'in' => $totalDebitBefore,
            'out' => $totalCreditBefore,
            'balance' => $initialBalance,
            'user' => (object) ['name' => ''],
            'is_closing_entry' => '-',
        ];

        // Variabel untuk menyimpan total in dan out selama periode
        $totalDebitDuring = 0;
        $totalCreditDuring = 0;

        foreach ($data as $row) {
            $runningBalance += ($row->in - $row->out);

            $in = $row->in != 0 ? $row->in : '-';
            $out = $row->out != 0 ? $row->out : '-';

            $results[] = [
                'created_at' => $row->created_at->format('Y-m-d'),
                'description' => $row->description,
                'in' => $in,
                'out' => $out,
                'balance' => $runningBalance,
                'user' => $row->user,
            ];

            // Update total in dan out selama periode
            $totalDebitDuring += $row->in;
            $totalCreditDuring += $row->out;
        }

        // Hitung saldo akhir berdasarkan normal_balance_id dari akun
        $finalBalance = $initialBalance;
        $finalBalance = $runningBalance;

        // Tambahkan saldo akhir sebagai baris terakhir
        $results[] = [
            'created_at' => $toDatetime,
            'description' => 'Saldo Akhir',
            'in' => $totalDebitDuring,
            'out' => $totalCreditDuring,
            'balance' => $finalBalance,
            'user' => (object) ['name' => ''],
        ];

        return Datatables::of(collect($results))
            ->addIndexColumn()
            ->make(true);
    }
}
