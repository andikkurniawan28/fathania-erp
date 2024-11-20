<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Business;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $suppliers = Supplier::all();
        return view('supplier.index', compact('setup', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $businesses = Business::all();
        return view('supplier.create', compact('setup', 'businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:suppliers",
            "phone_number" => "required|unique:suppliers",
            "address" => "required",
            "business_id" => "required",
        ]);
        $supplier = Supplier::create($validated);
        return redirect()->back()->with("success", "Supplier has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $supplier = Supplier::findOrFail($id);
        $businesses = Business::all();
        return view('supplier.edit', compact('setup', 'supplier', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:suppliers,name,' . $supplier->id,
            'phone_number' => 'required|unique:suppliers,name,' . $supplier->id,
            "address" => "required",
            "business_id" => "required",
        ]);
        $supplier->update($validated);
        return redirect()->route('supplier.index')->with("success", "Supplier has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Supplier has been deleted");
    }
}
