<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\StockAdjust;
use Illuminate\Http\Request;
use App\Models\NormalBalance;

class StockAdjustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $stock_adjusts = StockAdjust::all();
        return view('stock_adjust.index', compact('setup', 'stock_adjusts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $normal_balances = NormalBalance::all();
        return view('stock_adjust.create', compact('setup', 'normal_balances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:stock_adjusts",
            "stock_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $stock_adjust = StockAdjust::create($validated);
        return redirect()->back()->with("success", "StockAdjust has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAdjust $stock_adjust)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $stock_adjust = StockAdjust::findOrFail($id);
        $normal_balances = NormalBalance::all();
        return view('stock_adjust.edit', compact('setup', 'stock_adjust', 'normal_balances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stock_adjust = StockAdjust::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:stock_adjusts,name,' . $stock_adjust->id,
            "stock_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $stock_adjust->update($validated);
        return redirect()->route('stock_adjust.index')->with("success", "StockAdjust has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        StockAdjust::findOrFail($id)->delete();
        return redirect()->back()->with("success", "StockAdjust has been deleted");
    }
}
