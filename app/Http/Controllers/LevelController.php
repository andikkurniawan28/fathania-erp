<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Setup;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $levels = Level::all();
        return view('level.index', compact('setup', 'levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('level.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:levels",
        ]);
        $level = Level::create($validated);
        return redirect()->back()->with("success", "Level has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $level = Level::findOrFail($id);
        return view('level.edit', compact('setup', 'level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:levels,name,' . $level->id,
        ]);
        $level->update($validated);
        return redirect()->route('level.index')->with("success", "Level has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Level::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Level has been deleted");
    }
}
