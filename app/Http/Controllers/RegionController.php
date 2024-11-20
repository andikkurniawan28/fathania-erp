<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Setup;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $regions = Region::all();
        return view('region.index', compact('setup', 'regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('region.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:regions",
        ]);
        $region = Region::create($validated);
        return redirect()->back()->with("success", "Region has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $region = Region::findOrFail($id);
        return view('region.edit', compact('setup', 'region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:regions,name,' . $region->id,
        ]);
        $region->update($validated);
        return redirect()->route('region.index')->with("success", "Region has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Region::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Region has been deleted");
    }
}
