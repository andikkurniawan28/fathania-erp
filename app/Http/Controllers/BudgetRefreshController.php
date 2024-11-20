<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetRefreshController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($budget_id)
    {
        $budget = Budget::whereId($budget_id)->get()->last();
        $spent = Budget::countSpent($budget_id, $budget->start_date, $budget->end_date);
        $remaining = Budget::countRemaining($budget->amount, $spent);
        Budget::whereId($budget_id)
            ->update([
                "spent" => $spent,
                "remaining" => $remaining,
            ]);
        return redirect()->back()->with("success", "Budget {$budget->name} has been refreshed");
    }
}
