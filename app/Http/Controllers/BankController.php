<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Setup;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $banks = Bank::all();
        return view('bank.index', compact('setup', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('bank.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:banks",
        ]);
        $bank = Bank::create($validated);
        return redirect()->back()->with("success", "Bank has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $bank = Bank::findOrFail($id);
        return view('bank.edit', compact('setup', 'bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:banks,name,' . $bank->id,
        ]);
        $bank->update($validated);
        return redirect()->route('bank.index')->with("success", "Bank has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Bank has been deleted");
    }
}
