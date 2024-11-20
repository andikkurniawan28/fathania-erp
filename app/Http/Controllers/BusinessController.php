<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Setup;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $businesses = Business::all();
        return view('business.index', compact('setup', 'businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('business.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:businesses",
        ]);
        $business = Business::create($validated);
        return redirect()->back()->with("success", "Business has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $business = Business::findOrFail($id);
        return view('business.edit', compact('setup', 'business'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:businesses,name,' . $business->id,
        ]);
        $business->update($validated);
        return redirect()->route('business.index')->with("success", "Business has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Business::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Business has been deleted");
    }
}
