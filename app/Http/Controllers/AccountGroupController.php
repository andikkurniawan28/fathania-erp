<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use App\Models\NormalBalance;
use App\Models\FinancialStatement;

class AccountGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $account_groups = AccountGroup::all();
        return view('account_group.index', compact('setup', 'account_groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $financial_statements = FinancialStatement::all();
        $normal_balances = NormalBalance::all();
        return view('account_group.create', compact('setup', 'financial_statements', 'normal_balances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|unique:account_groups",
            "name" => "required|unique:account_groups",
            "financial_statement_id" => "required",
            "normal_balance_id" => "required",
        ]);
        $account_group = AccountGroup::create($validated);
        return redirect()->back()->with("success", "Account Group has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountGroup $account_group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $account_group = AccountGroup::findOrFail($id);
        $financial_statements = FinancialStatement::all();
        $normal_balances = NormalBalance::all();
        return view('account_group.edit', compact('setup', 'account_group', 'financial_statements', 'normal_balances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $account_group = AccountGroup::findOrFail($id);
        $validated = $request->validate([
            'id' => 'required|unique:account_groups,id,' . $account_group->id,
            'name' => 'required|unique:account_groups,name,' . $account_group->id,
            "financial_statement_id" => "required",
            "normal_balance_id" => "required",
        ]);
        $account_group->update($validated);
        return redirect()->route('account_group.index')->with("success", "Account Group has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AccountGroup::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Account Group has been deleted");
    }
}
