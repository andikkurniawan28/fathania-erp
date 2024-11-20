<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Ledger;
use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LedgerController extends Controller
{

    public function index(Request $request)
    {
        $setup = Setup::init();
        $accounts = Account::all();
        return view('ledger.index', compact('setup', 'accounts'));
    }

    public function data(Request $request)
    {
        $fromDatetime = $request->start_date;
        $toDatetime = $request->end_date;
        $accountId = $request->account_id;
        $yesterday = Carbon::parse($fromDatetime)->subDay()->format('Y-m-d');

        // Ambil data Ledger sebelum periode yang diminta untuk saldo awal
        $initialBalanceQuery = Ledger::where('account_id', $accountId)
            ->where('created_at', '<', $fromDatetime);

        // Hitung total debit dan credit sebelum periode yang diminta
        $totalDebitBefore = $initialBalanceQuery->sum('debit');
        $totalCreditBefore = $initialBalanceQuery->sum('credit');

        // Ambil data Ledger dalam periode yang diminta
        $data = Ledger::with('account', 'user')
            ->where('account_id', $accountId)
            ->whereBetween('created_at', [$fromDatetime, $toDatetime])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal berdasarkan normal_balance_id dari akun
        $initialBalance = 0;
        $account = $data->first()->account ?? null; // Dapatkan akun terkait

        if ($account) {
            if ($account->sub_account->main_account->account_group->normal_balance_id == 'D') {
                $initialBalance = ($totalDebitBefore - $totalCreditBefore) + $account->initial_balance;
            } elseif ($account->sub_account->main_account->account_group->normal_balance_id == 'C') {
                $initialBalance = ($totalCreditBefore - $totalDebitBefore) + $account->initial_balance;
            }
        }

        $runningBalance = $initialBalance; // Mulai dengan saldo awal

        // Menyiapkan data termasuk saldo awal sebagai baris pertama
        $results = [];
        $results[] = [
            'created_at' => $yesterday,
            'description' => 'Saldo Awal',
            'debit' => $totalDebitBefore,
            'credit' => $totalCreditBefore,
            'balance' => $initialBalance,
            'user' => (object) ['name' => ''],
            'is_closing_entry' => '-',
        ];

        // Variabel untuk menyimpan total debit dan credit selama periode
        $totalDebitDuring = 0;
        $totalCreditDuring = 0;

        foreach ($data as $row) {
            if ($row->account) {
                if ($row->account->sub_account->main_account->account_group->normal_balance_id == 'D') {
                    $runningBalance += ($row->debit - $row->credit);
                } elseif ($row->account->sub_account->main_account->account_group->normal_balance_id == 'C') {
                    $runningBalance += ($row->credit - $row->debit);
                }
            }

            $debit = $row->debit != 0 ? $row->debit : '-';
            $credit = $row->credit != 0 ? $row->credit : '-';

            $results[] = [
                'created_at' => $row->created_at->format('Y-m-d'),
                'description' => $row->description,
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $runningBalance,
                'user' => $row->user,
                'is_closing_entry' => $row->is_closing_entry,
            ];

            // Update total debit dan credit selama periode
            $totalDebitDuring += $row->debit;
            $totalCreditDuring += $row->credit;
        }

        // Hitung saldo akhir berdasarkan normal_balance_id dari akun
        $finalBalance = $initialBalance;
        if ($account) {
            if ($account->sub_account->main_account->account_group->normal_balance_id == 'D') {
                $finalBalance = $runningBalance;
            } elseif ($account->sub_account->main_account->account_group->normal_balance_id == 'C') {
                $finalBalance = $runningBalance;
            }
        }

        // Tambahkan saldo akhir sebagai baris terakhir
        $results[] = [
            'created_at' => $toDatetime,
            'description' => 'Saldo Akhir',
            'debit' => $totalDebitDuring,
            'credit' => $totalCreditDuring,
            'balance' => $finalBalance,
            'user' => (object) ['name' => ''],
            'is_closing_entry' => '-',
        ];

        return Datatables::of(collect($results))
            ->addIndexColumn()
            ->make(true);
    }
}
