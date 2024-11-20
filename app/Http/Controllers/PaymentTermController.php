<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerm;
use App\Models\Setup;
use Illuminate\Http\Request;

class PaymentTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $payment_terms = PaymentTerm::all();
        return view('payment_term.index', compact('setup', 'payment_terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('payment_term.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:payment_terms",
        ]);
        $payment_term = PaymentTerm::create($validated);
        return redirect()->back()->with("success", "PaymentTerm has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentTerm $payment_term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $payment_term = PaymentTerm::findOrFail($id);
        return view('payment_term.edit', compact('setup', 'payment_term'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $payment_term = PaymentTerm::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:payment_terms,name,' . $payment_term->id,
        ]);
        $payment_term->update($validated);
        return redirect()->route('payment_term.index')->with("success", "PaymentTerm has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PaymentTerm::findOrFail($id)->delete();
        return redirect()->back()->with("success", "PaymentTerm has been deleted");
    }
}
