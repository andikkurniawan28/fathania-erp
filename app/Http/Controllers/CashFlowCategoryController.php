<?php

namespace App\Http\Controllers;

use App\Models\CashFlowCategory;
use App\Models\Setup;
use Illuminate\Http\Request;

class CashFlowCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $cash_flow_categories = CashFlowCategory::all();
        return view('cash_flow_category.index', compact('setup', 'cash_flow_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('cash_flow_category.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:cash_flow_categories",
        ]);
        $cash_flow_category = CashFlowCategory::create($validated);
        return redirect()->back()->with("success", "Cash Flow Category has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(CashFlowCategory $cash_flow_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $cash_flow_category = CashFlowCategory::findOrFail($id);
        return view('cash_flow_category.edit', compact('setup', 'cash_flow_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cash_flow_category = CashFlowCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:cash_flow_categories,name,' . $cash_flow_category->id,
        ]);
        $cash_flow_category->update($validated);
        return redirect()->route('cash_flow_category.index')->with("success", "Cash Flow Category has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        CashFlowCategory::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Cash Flow Category has been deleted");
    }
}
