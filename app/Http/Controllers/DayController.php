<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Setup;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $days = Day::all();
        return view('day.index', compact('setup', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('day.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'num' => 'required',
            "name" => "required|unique:days",
            'salary_multiplier' => 'required',
        ]);
        $day = Day::create($validated);
        return redirect()->back()->with("success", "Day has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Day $day)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $day = Day::findOrFail($id);
        return view('day.edit', compact('setup', 'day'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $day = Day::findOrFail($id);
        $validated = $request->validate([
            // 'num' => 'required',
            'name' => 'required|unique:days,name,' . $day->id,
            'salary_multiplier' => 'required',
        ]);
        $day->update($validated);
        return redirect()->route('day.index')->with("success", "Day has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Day::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Day has been deleted");
    }
}
