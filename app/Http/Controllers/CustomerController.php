<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Business;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $customers = Customer::all();
        return view('customer.index', compact('setup', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $businesses = Business::all();
        return view('customer.create', compact('setup', 'businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:customers",
            "phone_number" => "required|unique:customers",
            "address" => "required",
            "business_id" => "required",
        ]);
        $customer = Customer::create($validated);
        return redirect()->back()->with("success", "Customer has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $customer = Customer::findOrFail($id);
        $businesses = Business::all();
        return view('customer.edit', compact('setup', 'customer', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:customers,name,' . $customer->id,
            'phone_number' => 'required|unique:customers,name,' . $customer->id,
            "address" => "required",
            "business_id" => "required",
        ]);
        $customer->update($validated);
        return redirect()->route('customer.index')->with("success", "Customer has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Customer has been deleted");
    }
}
