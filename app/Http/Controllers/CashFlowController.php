<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\CashFlowCategory;

class CashFlowController extends Controller
{
    public function index()
    {
        $setup = Setup::init();
        $cash_flow_categories = CashFlowCategory::with('account')->get();
        return view('cash_flow.index', compact('setup', 'cash_flow_categories'));
    }

    public function data($year, $month)
    {
        $month = (int)$month;

        $cash_flow_categories = CashFlowCategory::with('account')
            ->get();

        foreach($cash_flow_categories as $cash_flow_category)
        {
            foreach($cash_flow_category->account as $account){
                $account->total = Account::balanceSheet($year, $month, $account->id, $account->sub_account->main_account->account_group->normal_balance_id);
            }
        }

        return response()->json(['data' => $cash_flow_categories]);
    }
}
