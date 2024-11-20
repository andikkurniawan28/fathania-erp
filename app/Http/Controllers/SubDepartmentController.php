<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubDepartment;

class SubDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $sub_departments = SubDepartment::all();
        return view('sub_department.index', compact('setup', 'sub_departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $departments = Department::all();
        return view('sub_department.create', compact('setup', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:sub_departments",
            "department_id" => "required",
        ]);
        $sub_department = SubDepartment::create($validated);
        return redirect()->back()->with("success", "Sub Department has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(SubDepartment $sub_department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $sub_department = SubDepartment::findOrFail($id);
        $departments = Department::all();
        return view('sub_department.edit', compact('setup', 'sub_department', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sub_department = SubDepartment::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:sub_departments,name,' . $sub_department->id,
            "department_id" => "required",
        ]);
        $sub_department->update($validated);
        return redirect()->route('sub_department.index')->with("success", "Sub Department has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        SubDepartment::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Sub Department has been deleted");
    }
}
