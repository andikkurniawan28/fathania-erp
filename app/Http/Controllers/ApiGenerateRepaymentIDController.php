<?php

namespace App\Http\Controllers;

use App\Models\Repayment;
use Illuminate\Http\Request;
use App\Models\RepaymentCategory;

class ApiGenerateRepaymentIDController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($repayment_category_id)
    {
        $repaymentId = Repayment::generateID($repayment_category_id);
        $repayment_category = RepaymentCategory::findOrFail($repayment_category_id);
        return response()->json([
            'repayment_id' => $repaymentId,
            'repayment_category' => $repayment_category,
        ]);
    }
}
