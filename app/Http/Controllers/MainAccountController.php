<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\MainAccount;
use Illuminate\Http\Request;
use App\Models\AccountGroup;

class MainAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $main_accounts = MainAccount::all();
        return view('main_account.index', compact('setup', 'main_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $account_groups = AccountGroup::all();
        return view('main_account.create', compact('setup', 'account_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|unique:main_accounts",
            "name" => "required|unique:main_accounts",
            "account_group_id" => "required",
        ]);
        $main_account = MainAccount::create($validated);
        return redirect()->back()->with("success", "Main Account has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(MainAccount $main_account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $main_account = MainAccount::findOrFail($id);
        $account_groups = AccountGroup::all();
        return view('main_account.edit', compact('setup', 'main_account', 'account_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $main_account = MainAccount::findOrFail($id);
        $validated = $request->validate([
            'id' => 'required|unique:main_accounts,id,' . $main_account->id,
            'name' => 'required|unique:main_accounts,name,' . $main_account->id,
            "account_group_id" => "required",
        ]);
        $main_account->update($validated);
        return redirect()->route('main_account.index')->with("success", "Main Account has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MainAccount::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Main Account has been deleted");
    }
}
