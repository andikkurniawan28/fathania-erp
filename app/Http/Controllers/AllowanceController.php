<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $allowances = Allowance::all();
        return view('allowance.index', compact('setup', 'allowances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('allowance.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:allowances",
        ]);
        $allowance = Allowance::create($validated);
        return redirect()->back()->with("success", "Allowance has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Allowance $allowance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $allowance = Allowance::findOrFail($id);
        return view('allowance.edit', compact('setup', 'allowance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $allowance = Allowance::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:allowances,name,' . $allowance->id,
        ]);
        self::updateColumn($allowance, $request);
        $allowance->update($validated);
        return redirect()->route('allowance.index')->with("success", "Allowance has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Allowance::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Allowance has been deleted");
    }

    public static function updateColumn($allowance, $request)
    {
        $old_column_name = str_replace(' ', '_', $allowance->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $queries = [
            "ALTER TABLE materials CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` DOUBLE NULL",
        ];
        foreach ($queries as $query) {
            DB::statement($query);
        }
    }
}
