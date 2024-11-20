<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setup;
use App\Models\Feature;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $roles = Role::all();
        return view('role.index', compact('setup', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $features = Feature::all();
        return view('role.create', compact('setup', 'features'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:roles",
        ]);

        $role = Role::create($validated);

        if ($request->has('feature_ids')) {
            Permission::where("role_id", $role->id)->delete();
            $features = $request->input('feature_ids');
            foreach ($features as $featureId) {
                if (Feature::where('id', $featureId)->exists()) {
                    Permission::create([
                        'feature_id' => $featureId,
                        'role_id' => $role->id,
                    ]);
                }
            }
        }

        return redirect()->back()->with("success", "Role has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $role = Role::findOrFail($id);
        $features = Feature::all();
        $permissions = Permission::where('role_id', $id)->get();
        return view('role.edit', compact('setup', 'role', 'features', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        if ($request->has('feature_ids')) {
            Permission::where("role_id", $id)->delete();
            $features = $request->input('feature_ids');
            foreach ($features as $featureId) {
                if (Feature::where('id', $featureId)->exists()) {
                    Permission::create([
                        'feature_id' => $featureId,
                        'role_id' => $id,
                    ]);
                }
            }
        }

        $role->update($validated);
        return redirect()->back()->with("success", "Role has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Role has been deleted");
    }
}
