<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Setup;
use App\Models\Title;
use Illuminate\Http\Request;
use App\Models\SubDepartment;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $titles = Title::all();
        return view('title.index', compact('setup', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $sub_departments = SubDepartment::all();
        $levels = Level::all();
        return view('title.create', compact('setup', 'sub_departments', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:titles",
            "sub_department_id" => "required",
            "level_id" => "required",
            "incentive" => "required",
            "salary_multiplier" => "required",
        ]);
        $title = Title::create($validated);
        return redirect()->back()->with("success", "Title has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Title $title)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $title = Title::findOrFail($id);
        $sub_departments = SubDepartment::all();
        $levels = Level::all();
        return view('title.edit', compact('setup', 'title', 'sub_departments', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:titles,name,' . $title->id,
            "sub_department_id" => "required",
            "level_id" => "required",
            "incentive" => "required",
            "salary_multiplier" => "required",
        ]);
        $title->update($validated);
        return redirect()->route('title.index')->with("success", "Title has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Title::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Title has been deleted");
    }
}
