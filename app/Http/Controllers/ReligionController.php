<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use App\Models\Setup;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $religions = Religion::all();
        return view('religion.index', compact('setup', 'religions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('religion.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:religions",
        ]);
        $religion = Religion::create($validated);
        return redirect()->back()->with("success", "Religion has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Religion $religion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $religion = Religion::findOrFail($id);
        return view('religion.edit', compact('setup', 'religion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $religion = Religion::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:religions,name,' . $religion->id,
        ]);
        $religion->update($validated);
        return redirect()->route('religion.index')->with("success", "Religion has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Religion::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Religion has been deleted");
    }
}
