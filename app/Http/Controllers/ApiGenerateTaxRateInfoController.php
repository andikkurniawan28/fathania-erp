<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;

class ApiGenerateTaxRateInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($tax_rate_id)
    {
        $tax_rate = TaxRate::whereId($tax_rate_id)->get()->last();
        return response()->json(['tax_rate' => $tax_rate]);
    }
}
