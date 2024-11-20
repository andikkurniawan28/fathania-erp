<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Setup;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $campuses = Campus::all();
        return view('campus.index', compact('setup', 'campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('campus.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:campuses",
        ]);
        $campus = Campus::create($validated);
        return redirect()->back()->with("success", "Campus has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $campus = Campus::findOrFail($id);
        return view('campus.edit', compact('setup', 'campus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $campus = Campus::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:campuses,name,' . $campus->id,
        ]);
        $campus->update($validated);
        return redirect()->route('campus.index')->with("success", "Campus has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Campus::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Campus has been deleted");
    }
}
