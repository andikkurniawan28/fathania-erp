<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Setup;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $majors = Major::all();
        return view('major.index', compact('setup', 'majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('major.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:majors",
        ]);
        $major = Major::create($validated);
        return redirect()->back()->with("success", "Major has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $major = Major::findOrFail($id);
        return view('major.edit', compact('setup', 'major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:majors,name,' . $major->id,
        ]);
        $major->update($validated);
        return redirect()->route('major.index')->with("success", "Major has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Major::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Major has been deleted");
    }
}
