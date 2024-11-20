<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceCategory;

class ApiGenerateInvoiceIDController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($invoice_category_id)
    {
        $invoiceId = Invoice::generateID($invoice_category_id);
        $invoice_category = InvoiceCategory::findOrFail($invoice_category_id);
        return response()->json([
            'invoice_id' => $invoiceId,
            'invoice_category' => $invoice_category,
        ]);
    }
}
