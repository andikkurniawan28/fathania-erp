<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $deductions = Deduction::all();
        return view('deduction.index', compact('setup', 'deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('deduction.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:deductions",
        ]);
        $deduction = Deduction::create($validated);
        return redirect()->back()->with("success", "Deduction has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $deduction = Deduction::findOrFail($id);
        return view('deduction.edit', compact('setup', 'deduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $deduction = Deduction::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:deductions,name,' . $deduction->id,
        ]);
        self::updateColumn($deduction, $request);
        $deduction->update($validated);
        return redirect()->route('deduction.index')->with("success", "Deduction has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Deduction::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Deduction has been deleted");
    }

    public static function updateColumn($deduction, $request)
    {
        $old_column_name = str_replace(' ', '_', $deduction->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $queries = [
            "ALTER TABLE materials CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` DOUBLE NULL",
        ];
        foreach ($queries as $query) {
            DB::statement($query);
        }
    }
}
