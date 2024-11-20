<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Setup;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $shifts = Shift::all();
        return view('shift.index', compact('setup', 'shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('shift.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:shifts",
            "start" => "required",
            "finish" => "required",
            "start_break" => "nullable",
            "finish_break" => "nullable",
            // "delta_day_of_start" => "required",
            // "delta_day_of_finish" => "required",
        ]);
        $shift = Shift::create($validated);
        return redirect()->back()->with("success", "Shift has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $shift = Shift::findOrFail($id);
        return view('shift.edit', compact('setup', 'shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:shifts,name,' . $shift->id,
            "start" => "required",
            "finish" => "required",
            "start_break" => "nullable",
            "finish_break" => "nullable",
            // "delta_day_of_start" => "required",
            // "delta_day_of_finish" => "required",
        ]);
        $shift->update($validated);
        return redirect()->route('shift.index')->with("success", "Shift has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Shift::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Shift has been deleted");
    }
}
