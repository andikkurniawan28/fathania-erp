<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use App\Models\MainAccount;

class SubAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $sub_accounts = SubAccount::all();
        return view('sub_account.index', compact('setup', 'sub_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $main_accounts = MainAccount::all();
        return view('sub_account.create', compact('setup', 'main_accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|unique:sub_accounts",
            "name" => "required|unique:sub_accounts",
            "main_account_id" => "required",
        ]);
        $sub_account = SubAccount::create($validated);
        return redirect()->back()->with("success", "Sub Account has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(SubAccount $sub_account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $sub_account = SubAccount::findOrFail($id);
        $main_accounts = MainAccount::all();
        return view('sub_account.edit', compact('setup', 'sub_account', 'main_accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sub_account = SubAccount::findOrFail($id);
        $validated = $request->validate([
            'id' => 'required|unique:sub_accounts,id,' . $sub_account->id,
            'name' => 'required|unique:sub_accounts,name,' . $sub_account->id,
            "main_account_id" => "required",
        ]);
        $sub_account->update($validated);
        return redirect()->route('sub_account.index')->with("success", "Sub Account has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        SubAccount::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Sub Account has been deleted");
    }
}
