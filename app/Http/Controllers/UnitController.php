<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $units = Unit::all();
        return view('unit.index', compact('setup', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('unit.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:units",
        ]);
        $unit = Unit::create($validated);
        return redirect()->back()->with("success", "Unit has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $unit = Unit::findOrFail($id);
        return view('unit.edit', compact('setup', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id,
        ]);
        $unit->update($validated);
        return redirect()->route('unit.index')->with("success", "Unit has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Unit::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Unit has been deleted");
    }
}
