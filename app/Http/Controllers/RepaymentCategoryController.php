<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\NormalBalance;
use App\Models\RepaymentCategory;

class RepaymentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $repayment_categories = RepaymentCategory::all();
        return view('repayment_category.index', compact('setup', 'repayment_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $accounts = Account::all();
        $normal_balances = NormalBalance::all();
        return view('repayment_category.create', compact('setup', 'accounts', 'normal_balances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|string|unique:repayment_categories,id",
            "name" => "required|string|unique:repayment_categories,name",
            "deal_with" => "required|string|in:customers,suppliers",
            "grand_total_account_id" => "required|string|exists:accounts,id",
            "grand_total_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $repayment_category = RepaymentCategory::create($validated);
        return redirect()->back()->with("success", "Repayment Category has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(RepaymentCategory $repayment_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $repayment_category = RepaymentCategory::findOrFail($id);
        $accounts = Account::all();
        $normal_balances = NormalBalance::all();
        return view('repayment_category.edit', compact('setup', 'repayment_category', 'accounts', 'normal_balances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $repayment_category = RepaymentCategory::findOrFail($id);
        $validated = $request->validate([
            // 'id' => 'required|unique:repayment_categories,id,' . $repayment_category->id,
            'name' => 'required|unique:repayment_categories,name,' . $repayment_category->id,
            "deal_with" => "required|string|in:customers,suppliers",
            "grand_total_account_id" => "required|string|exists:accounts,id",
            "grand_total_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $repayment_category->update($validated);
        return redirect()->route('repayment_category.index')->with("success", "Repayment Category has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        RepaymentCategory::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Repayment Category has been deleted");
    }
}
