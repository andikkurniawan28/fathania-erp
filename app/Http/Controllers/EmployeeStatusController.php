<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStatus;
use App\Models\Setup;
use Illuminate\Http\Request;

class EmployeeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $employee_statuses = EmployeeStatus::all();
        return view('employee_status.index', compact('setup', 'employee_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('employee_status.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:employee_statuses",
            "incentive" => "required",
            "salary_multiplier" => "required",
        ]);
        $employee_status = EmployeeStatus::create($validated);
        return redirect()->back()->with("success", "Employee Status has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeStatus $employee_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $employee_status = EmployeeStatus::findOrFail($id);
        return view('employee_status.edit', compact('setup', 'employee_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee_status = EmployeeStatus::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:employee_statuses,name,' . $employee_status->id,
            "incentive" => "required",
            "salary_multiplier" => "required",
        ]);
        $employee_status->update($validated);
        return redirect()->route('employee_status.index')->with("success", "Employee Status has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        EmployeeStatus::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Employee Status has been deleted");
    }
}
