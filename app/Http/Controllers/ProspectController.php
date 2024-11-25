<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Business;
use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $prospects = Prospect::all();
        return view('prospect.index', compact('setup', 'prospects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $businesses = Business::all();
        return view('prospect.create', compact('setup', 'businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:prospects",
            "phone_number" => "required|unique:prospects",
            "address" => "required",
            "business_id" => "required",
        ]);
        $prospect = Prospect::create($validated);
        return redirect()->back()->with("success", "Prospect has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Prospect $prospect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $prospect = Prospect::findOrFail($id);
        $businesses = Business::all();
        return view('prospect.edit', compact('setup', 'prospect', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $prospect = Prospect::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:prospects,name,' . $prospect->id,
            'phone_number' => 'required|unique:prospects,name,' . $prospect->id,
            "address" => "required",
            "business_id" => "required",
        ]);
        $prospect->update($validated);
        return redirect()->route('prospect.index')->with("success", "Prospect has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Prospect::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Prospect has been deleted");
    }
}
