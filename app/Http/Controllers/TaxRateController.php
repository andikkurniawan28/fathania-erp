<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Models\Setup;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $tax_rates = TaxRate::all();
        return view('tax_rate.index', compact('setup', 'tax_rates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('tax_rate.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add(['rate' => Setup::checkFormat($request->rate)]);
        $validated = $request->validate([
            "name" => "required|unique:tax_rates",
            "rate" => "required",
        ]);
        $tax_rate = TaxRate::create($validated);
        return redirect()->back()->with("success", "Tax Rate has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxRate $tax_rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $tax_rate = TaxRate::findOrFail($id);
        return view('tax_rate.edit', compact('setup', 'tax_rate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tax_rate = TaxRate::findOrFail($id);
        $request->request->add(['rate' => Setup::checkFormat($request->rate)]);
        $validated = $request->validate([
            'name' => 'required|unique:tax_rates,name,' . $tax_rate->id,
            "rate" => "required",
        ]);
        $tax_rate->update($validated);
        return redirect()->route('tax_rate.index')->with("success", "Tax Rate has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        TaxRate::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Tax Rate has been deleted");
    }
}
