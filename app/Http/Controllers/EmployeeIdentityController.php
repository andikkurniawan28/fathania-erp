<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\EmployeeIdentity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeIdentityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $employee_identities = EmployeeIdentity::all();
        return view('employee_identity.index', compact('setup', 'employee_identities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('employee_identity.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:employee_identities",
        ]);
        $employee_identity = EmployeeIdentity::create($validated);
        return redirect()->back()->with("success", "Employee Identity has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeIdentity $employee_identity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $employee_identity = EmployeeIdentity::findOrFail($id);
        return view('employee_identity.edit', compact('setup', 'employee_identity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee_identity = EmployeeIdentity::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:employee_identities,name,' . $employee_identity->id,
        ]);
        self::updateColumn($employee_identity, $request);
        $employee_identity->update($validated);
        return redirect()->route('employee_identity.index')->with("success", "Employee Identity has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        EmployeeIdentity::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Employee Identity has been deleted");
    }

    public static function updateColumn($employee_identity, $request)
    {
        $old_column_name = str_replace(' ', '_', $employee_identity->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $queries = [
            "ALTER TABLE employees CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` VARCHAR(255) NULL",
        ];
        foreach ($queries as $query) {
            DB::statement($query);
        }
    }
}
