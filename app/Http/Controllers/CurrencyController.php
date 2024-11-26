<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Setup;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $currencys = Currency::all();
        return view('currency.index', compact('setup', 'currencys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('currency.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:currencies",
            "symbol" => "required",
            "thousand_separator" => "required",
            "decimal_separator" => "required",
        ]);
        $currency = Currency::create($validated);
        return redirect()->back()->with("success", "Currency has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $currency = Currency::findOrFail($id);
        return view('currency.edit', compact('setup', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:currencys,name,' . $currency->id,
            "symbol" => "required",
            "thousand_separator" => "required",
            "decimal_separator" => "required",
        ]);
        $currency->update($validated);
        return redirect()->route('currency.index')->with("success", "Currency has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Currency has been deleted");
    }
}
