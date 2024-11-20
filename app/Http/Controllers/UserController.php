<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Setup;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $users = User::all();
        return view('user.index', compact('setup', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $roles = Role::all();
        return view('user.create', compact('setup', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "role_id" => "required",
            "name" => "required",
            "username" => "required|unique:users",
            "password" => "required",
        ]);
        $validated['password'] = bcrypt($request->password);
        User::create($validated);
        return redirect()->back()->with("success", "User has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('user.edit', compact('setup', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            "role_id" => "required",
            "name" => "required",
            'username' => 'required|unique:users,username,' . $user->id,
            "is_active" => "required",
            "password" => "nullable|min:8", // Jika password diinputkan, minimal 8 karakter
        ]);

        // Jika ada input password baru, bcrypt password
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        // Update data pengguna
        $user->update($validated);

        return redirect()->back()->with("success", "User has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with("success", "User has been deleted");
    }
}
