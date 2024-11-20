<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Setup;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $departments = Department::all();
        return view('department.index', compact('setup', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('department.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:departments",
        ]);
        $department = Department::create($validated);
        return redirect()->back()->with("success", "Department has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $department = Department::findOrFail($id);
        return view('department.edit', compact('setup', 'department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
        ]);
        $department->update($validated);
        return redirect()->route('department.index')->with("success", "Department has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Department has been deleted");
    }
}
