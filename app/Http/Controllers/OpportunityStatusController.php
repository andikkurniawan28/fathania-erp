<?php

namespace App\Http\Controllers;

use App\Models\OpportunityStatus;
use App\Models\Setup;
use Illuminate\Http\Request;

class OpportunityStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $opportunity_statuses = OpportunityStatus::all();
        return view('opportunity_status.index', compact('setup', 'opportunity_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('opportunity_status.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:opportunity_statuses",
        ]);
        $opportunity_status = OpportunityStatus::create($validated);
        return redirect()->back()->with("success", "OpportunityStatus has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(OpportunityStatus $opportunity_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $opportunity_status = OpportunityStatus::findOrFail($id);
        return view('opportunity_status.edit', compact('setup', 'opportunity_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $opportunity_status = OpportunityStatus::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:opportunity_statuses,name,' . $opportunity_status->id,
        ]);
        $opportunity_status->update($validated);
        return redirect()->route('opportunity_status.index')->with("success", "OpportunityStatus has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        OpportunityStatus::findOrFail($id)->delete();
        return redirect()->back()->with("success", "OpportunityStatus has been deleted");
    }
}
