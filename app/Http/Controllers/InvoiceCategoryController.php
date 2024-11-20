<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\NormalBalance;
use App\Models\InvoiceCategory;

class InvoiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $invoice_categories = InvoiceCategory::all();
        return view('invoice_category.index', compact('setup', 'invoice_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $accounts = Account::all();
        $normal_balances = NormalBalance::all();
        return view('invoice_category.create', compact('setup', 'accounts', 'normal_balances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "id" => "required|string|unique:invoice_categories,id",
            "name" => "required|string|unique:invoice_categories,name",
            "deal_with" => "required|string|in:customers,suppliers",
            "stock_normal_balance_id" => "required|string|exists:normal_balances,id",
            "subtotal_account_id" => "required|string|exists:accounts,id",
            "subtotal_normal_balance_id" => "required|string|exists:normal_balances,id",
            "taxes_account_id" => "required|string|exists:accounts,id",
            "taxes_normal_balance_id" => "required|string|exists:normal_balances,id",
            "freight_account_id" => "required|string|exists:accounts,id",
            "freight_normal_balance_id" => "required|string|exists:normal_balances,id",
            "discount_account_id" => "required|string|exists:accounts,id",
            "discount_normal_balance_id" => "required|string|exists:normal_balances,id",
            "grand_total_account_id" => "required|string|exists:accounts,id",
            "grand_total_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $invoice_category = InvoiceCategory::create($validated);
        return redirect()->back()->with("success", "Invoice Category has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceCategory $invoice_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $invoice_category = InvoiceCategory::findOrFail($id);
        $accounts = Account::all();
        $normal_balances = NormalBalance::all();
        return view('invoice_category.edit', compact('setup', 'invoice_category', 'accounts', 'normal_balances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice_category = InvoiceCategory::findOrFail($id);
        $validated = $request->validate([
            // 'id' => 'required|unique:invoice_categories,id,' . $invoice_category->id,
            'name' => 'required|unique:invoice_categories,name,' . $invoice_category->id,
            "deal_with" => "required|string|in:customers,suppliers",
            "stock_normal_balance_id" => "required|string|exists:normal_balances,id",
            "subtotal_account_id" => "required|string|exists:accounts,id",
            "subtotal_normal_balance_id" => "required|string|exists:normal_balances,id",
            "taxes_account_id" => "required|string|exists:accounts,id",
            "taxes_normal_balance_id" => "required|string|exists:normal_balances,id",
            "freight_account_id" => "required|string|exists:accounts,id",
            "freight_normal_balance_id" => "required|string|exists:normal_balances,id",
            "discount_account_id" => "required|string|exists:accounts,id",
            "discount_normal_balance_id" => "required|string|exists:normal_balances,id",
            "grand_total_account_id" => "required|string|exists:accounts,id",
            "grand_total_normal_balance_id" => "required|string|exists:normal_balances,id",
        ]);
        $invoice_category->update($validated);
        return redirect()->route('invoice_category.index')->with("success", "Invoice Category has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        InvoiceCategory::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Invoice Category has been deleted");
    }
}
