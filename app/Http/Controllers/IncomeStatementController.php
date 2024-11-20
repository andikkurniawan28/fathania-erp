<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Account;
use App\Models\SubAccount;
use App\Models\MainAccount;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{
    public function index()
    {
        $setup = Setup::init();
        $account_groups = AccountGroup::where('financial_statement_id', 'I')->get();
        return view('income_statement.index', compact('setup', 'account_groups'));
    }

    public function data($year, $month)
    {
        $month = (int)$month;

        $account_groups = AccountGroup::with('main_account.sub_account.account')
            ->where('financial_statement_id', 'I')
            ->get();

        foreach($account_groups as $account_group)
        {
            $account_group->income_statement = AccountGroup::incomeStatement($year, $month, $account_group->id, $account_group->normal_balance_id);
            foreach($account_group->main_account as $main_account){
                $main_account->income_statement = MainAccount::incomeStatement($year, $month, $main_account->id, $account_group->normal_balance_id);
                foreach($main_account->sub_account as $sub_account){
                    $sub_account->income_statement = SubAccount::incomeStatement($year, $month, $sub_account->id, $account_group->normal_balance_id);
                    foreach($sub_account->account as $account){
                        $account->income_statement = Account::incomeStatement($year, $month, $account->id, $account_group->normal_balance_id);
                    }
                }
            }
        }

        return response()->json(['data' => $account_groups]);
    }
}
