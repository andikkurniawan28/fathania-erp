<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\RepaymentCategory;
use App\Models\InvoiceCategory;

class ApiGenerateUnpaidInvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($repayment_category_id, $supplier_customer_id)
    {
        $repayment_category = RepaymentCategory::findOrFail($repayment_category_id);
        if($repayment_category->deal_with == "suppliers"){
            $data = Invoice::where("supplier_id", $supplier_customer_id)->where("left", ">", 0)->get();
        } else {
            $data = Invoice::where("customer", $supplier_customer_id)->where("left", ">", 0)->get();
        }
        return response()->json([
            'data' => $data,
        ]);
    }
}
