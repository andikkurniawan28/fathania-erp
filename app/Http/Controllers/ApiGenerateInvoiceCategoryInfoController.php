<?php

namespace App\Http\Controllers;

use App\Models\InvoiceCategory;
use Illuminate\Http\Request;

class ApiGenerateInvoiceCategoryInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($invoice_category_id)
    {
        $invoice_category = InvoiceCategory::whereId($invoice_category_id)->get()->last();
        return response()->json(['invoice_category' => $invoice_category]);
    }
}
