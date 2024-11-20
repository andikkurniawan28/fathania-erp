<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Business;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $vendors = Vendor::all();
        return view('vendor.index', compact('setup', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $businesses = Business::all();
        return view('vendor.create', compact('setup', 'businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:vendors",
            "phone_number" => "required|unique:vendors",
            "address" => "required",
            "business_id" => "required",
        ]);
        $vendor = Vendor::create($validated);
        return redirect()->back()->with("success", "Vendor has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $vendor = Vendor::findOrFail($id);
        $businesses = Business::all();
        return view('vendor.edit', compact('setup', 'vendor', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:vendors,name,' . $vendor->id,
            'phone_number' => 'required|unique:vendors,name,' . $vendor->id,
            "address" => "required",
            "business_id" => "required",
        ]);
        $vendor->update($validated);
        return redirect()->route('vendor.index')->with("success", "Vendor has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Vendor has been deleted");
    }
}
