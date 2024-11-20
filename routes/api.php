<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiCountSalaryController;
use App\Http\Controllers\ApiGenerateInvoiceIDController;
use App\Http\Controllers\ApiGenerateValidUntilController;
use App\Http\Controllers\ApiGenerateRepaymentIDController;
use App\Http\Controllers\ApiGenerateTaxRateInfoController;
use App\Http\Controllers\ApiGenerateMaterialInfoController;
use App\Http\Controllers\ApiGenerateUnpaidInvoiceController;
use App\Http\Controllers\ApiGenerateInvoiceCategoryInfoController;
use App\Http\Controllers\ApiGenerateRepaymentCategoryInfoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("generate_invoice_id/{invoice_category_id}", ApiGenerateInvoiceIDController::class)
    ->name("api.generate_invoice_id");
Route::get("generate_valid_until/{payment_term_id}/{current_date}", ApiGenerateValidUntilController::class)
    ->name("api.generate_valid_until");
Route::get("generate_material_info/{material_id}", ApiGenerateMaterialInfoController::class)
    ->name("api.generate_material_info");
Route::get("generate_invoice_category_info/{invoice_category_id}", ApiGenerateInvoiceCategoryInfoController::class)
    ->name("api.generate_invoice_category_info");
Route::get("generate_tax_rate_info/{tax_rate_id}", ApiGenerateTaxRateInfoController::class)
    ->name("api.generate_tax_rate_info");
Route::get("generate_repayment_id/{repayment_category_id}", ApiGenerateRepaymentIDController::class)
    ->name("api.generate_repayment_id");
Route::get("generate_repayment_category_info/{repayment_category_id}", ApiGenerateRepaymentCategoryInfoController::class)
    ->name("api.generate_repayment_category_info");
Route::get("generate_unpaid_invoice/{repayment_category_id}/{supplier_customer_id}", ApiGenerateUnpaidInvoiceController::class)
    ->name("api.generate_unpaid_invoice_info");
Route::get("count_salary/{employee_id}/{from}/{to}", ApiCountSalaryController::class)
    ->name("api.count_salary");
