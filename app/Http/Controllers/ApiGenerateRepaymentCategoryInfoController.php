<?php

namespace App\Http\Controllers;

use App\Models\RepaymentCategory;
use Illuminate\Http\Request;

class ApiGenerateRepaymentCategoryInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($repayment_category_id)
    {
        $repayment_category = RepaymentCategory::whereId($repayment_category_id)->get()->last();
        return response()->json(['repayment_category' => $repayment_category]);
    }
}
