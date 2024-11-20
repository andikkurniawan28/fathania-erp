<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Setup;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $skills = Skill::all();
        return view('skill.index', compact('setup', 'skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('skill.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:skills",
        ]);
        $skill = Skill::create($validated);
        return redirect()->back()->with("success", "Skill has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $skill = Skill::findOrFail($id);
        return view('skill.edit', compact('setup', 'skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:skills,name,' . $skill->id,
        ]);
        $skill->update($validated);
        return redirect()->route('skill.index')->with("success", "Skill has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Skill::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Skill has been deleted");
    }
}
