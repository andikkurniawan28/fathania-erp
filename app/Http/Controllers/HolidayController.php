<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Setup;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $holidays = Holiday::all();
        return view('holiday.index', compact('setup', 'holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('holiday.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:holidays",
        ]);
        $holiday = Holiday::create($validated);
        return redirect()->back()->with("success", "Holiday has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $holiday = Holiday::findOrFail($id);
        return view('holiday.edit', compact('setup', 'holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:holidays,name,' . $holiday->id,
        ]);
        $holiday->update($validated);
        return redirect()->route('holiday.index')->with("success", "Holiday has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Holiday::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Holiday has been deleted");
    }
}
