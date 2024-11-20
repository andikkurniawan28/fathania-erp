<?php

namespace App\Http\Controllers;

use App\Models\MaritalStatus;
use App\Models\Setup;
use Illuminate\Http\Request;

class MaritalStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $marital_statuses = MaritalStatus::all();
        return view('marital_status.index', compact('setup', 'marital_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('marital_status.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:marital_statuses",
        ]);
        $marital_status = MaritalStatus::create($validated);
        return redirect()->back()->with("success", "Marital Status has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(MaritalStatus $marital_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $marital_status = MaritalStatus::findOrFail($id);
        return view('marital_status.edit', compact('setup', 'marital_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marital_status = MaritalStatus::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:marital_statuses,name,' . $marital_status->id,
        ]);
        $marital_status->update($validated);
        return redirect()->route('marital_status.index')->with("success", "Marital Status has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MaritalStatus::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Marital Status has been deleted");
    }
}
