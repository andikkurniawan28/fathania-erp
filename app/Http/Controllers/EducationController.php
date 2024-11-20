<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Setup;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $educations = Education::all();
        return view('education.index', compact('setup', 'educations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('education.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:educations",
        ]);
        $education = Education::create($validated);
        return redirect()->back()->with("success", "Education has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Education $education)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $education = Education::findOrFail($id);
        return view('education.edit', compact('setup', 'education'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $education = Education::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:educations,name,' . $education->id,
        ]);
        $education->update($validated);
        return redirect()->route('education.index')->with("success", "Education has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Education::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Education has been deleted");
    }
}
