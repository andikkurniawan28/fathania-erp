<?php

namespace App\Http\Controllers;

use App\Models\NormalBalance;
use App\Models\Setup;
use Illuminate\Http\Request;

class NormalBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $normal_balances = NormalBalance::all();
        return view('normal_balance.index', compact('setup', 'normal_balances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('normal_balance.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|unique:normal_balances",
            "name" => "required|unique:normal_balances",
        ]);
        $normal_balance = NormalBalance::create($validated);
        return redirect()->back()->with("success", "Normal Balance has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(NormalBalance $normal_balance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $normal_balance = NormalBalance::findOrFail($id);
        return view('normal_balance.edit', compact('setup', 'normal_balance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $normal_balance = NormalBalance::findOrFail($id);
        $validated = $request->validate([
            'id' => 'required|unique:normal_balances,id,' . $normal_balance->id,
            'name' => 'required|unique:normal_balances,name,' . $normal_balance->id,
        ]);
        $normal_balance->update($validated);
        return redirect()->route('normal_balance.index')->with("success", "Normal Balance has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        NormalBalance::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Normal Balance has been deleted");
    }
}
