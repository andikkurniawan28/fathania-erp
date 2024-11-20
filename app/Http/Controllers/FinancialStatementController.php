<?php

namespace App\Http\Controllers;

use App\Models\FinancialStatement;
use App\Models\Setup;
use Illuminate\Http\Request;

class FinancialStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $financial_statements = FinancialStatement::all();
        return view('financial_statement.index', compact('setup', 'financial_statements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('financial_statement.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|unique:financial_statements",
            "name" => "required|unique:financial_statements",
        ]);
        $financial_statement = FinancialStatement::create($validated);
        return redirect()->back()->with("success", "Financial Statement has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialStatement $financial_statement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $financial_statement = FinancialStatement::findOrFail($id);
        return view('financial_statement.edit', compact('setup', 'financial_statement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $financial_statement = FinancialStatement::findOrFail($id);
        $validated = $request->validate([
            'id' => 'required|unique:financial_statements,id,' . $financial_statement->id,
            'name' => 'required|unique:financial_statements,name,' . $financial_statement->id,
        ]);
        $financial_statement->update($validated);
        return redirect()->route('financial_statement.index')->with("success", "Financial Statement has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        FinancialStatement::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Financial Statement has been deleted");
    }
}
