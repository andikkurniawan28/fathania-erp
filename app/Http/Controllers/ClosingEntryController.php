<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Journal;
use App\Models\SubAccount;
use App\Models\MainAccount;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use App\Models\JournalDetail;

class ClosingEntryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $year = $request->year;
        $month = (int)$request->month;
        $setup = Setup::init();

        $closed_entries = Ledger::whereMonth("created_at", $month)->whereYear("created_at", $year)
            ->where("is_closing_entry", 1)
            ->get();

        foreach($closed_entries as $closed_entry)
        {
            Journal::whereId($closed_entry->journal_id)->delete();
        }

        // Buat jurnal Penutupan, debit dan credit diisi diakhir karena belum dapat angkanya
        $total_debit = 0;
        $total_credit = 0;
        $item_order = 1;
        $journal_id = Journal::generateID();
        Journal::create([
            "id" => $journal_id,
            "user_id" => auth()->id(),
            "debit" => 0,
            "credit" => 0,
        ]);

        $account_groups = AccountGroup::with('main_account.sub_account.account')
            ->where('financial_statement_id', 'I')
            ->get();

        foreach($account_groups as $account_group)
        {
            // Untuk mendapat nilai saldo dari account
            $account_group->income_statement = AccountGroup::incomeStatement($year, $month, $account_group->id, $account_group->normal_balance_id);
            foreach($account_group->main_account as $main_account){
                $main_account->income_statement = MainAccount::incomeStatement($year, $month, $main_account->id, $account_group->normal_balance_id);
                foreach($main_account->sub_account as $sub_account){
                    $sub_account->income_statement = SubAccount::incomeStatement($year, $month, $sub_account->id, $account_group->normal_balance_id);
                    foreach($sub_account->account as $account){

                        $account->income_statement = Account::incomeStatement($year, $month, $account->id, $account_group->normal_balance_id);

                        // Jika ada saldo di akun tersebut
                        if($account->income_statement != 0)
                        {
                            // Negasikan berdasarkan normal_balance_id nya
                            if($account_group->normal_balance_id == "D") {
                                $debit = 0;
                                $credit = $account->income_statement;
                            }
                            else {
                                $credit = 0;
                                $debit = $account->income_statement;
                            }

                            // Jika income_statement nya ada maka, buat detail jurnal tutupan untuk akun tersebut
                            JournalDetail::create([
                                "journal_id" => $journal_id,
                                "account_id" => $account->id,
                                "description" => "Closing Entry for {$year}-{$month}",
                                "item_order" => $item_order,
                                "credit" => $credit,
                                "debit" => $debit,
                            ]);

                            // Masukkan juga detail jurnal ke buku besar
                            Ledger::create([
                                "journal_id" => $journal_id,
                                "account_id" => $account->id,
                                "user_id" => auth()->id(),
                                "description" => "Closing Entry for {$journal_id}-{$year}-{$month}",
                                "credit" => $credit,
                                "debit" => $debit,
                                "is_closing_entry" => 1,
                            ]);

                            $total_debit += $debit;
                            $total_credit += $credit;
                            $item_order++;
                        }
                    }
                }
            }
        }

        // Hitung net_income
        // $net_income = $total_credit - $total_debit;
        $net_income = $total_debit - $total_credit;
        $akun_laba_ditahan = $setup->retained_earning_id;

        // Tambahkan net_income ke Journal Detail supaya Jurnalnya balance
        JournalDetail::create([
            "journal_id" => $journal_id,
            "account_id" => $akun_laba_ditahan,
            "description" => "Closing Entry for {$year}-{$month}",
            "item_order" => $item_order,
            "credit" => $net_income,
            "debit" => 0,
        ]);

        // Update debit dan credit di journal
        Journal::whereId($journal_id)->update([
            "debit" => $total_debit,
            "credit" => $total_credit + $net_income,
        ]);

        // Pindah ke akun net_income ke Laba Ditahan
        Ledger::insert([
            "journal_id" => $journal_id,
            "account_id" => $akun_laba_ditahan,
            "user_id" => auth()->id(),
            "description" => "Closing Entry for {$journal_id}-{$year}-{$month}",
            "credit" => $net_income,
            "debit" => 0,
            "is_closing_entry" => 1,
        ]);

        return redirect()->back()->with('success', 'Income Statement for this period closed successfully.');
    }
}
