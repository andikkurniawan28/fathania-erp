<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ChecklogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\RepaymentController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\MainAccountController;
use App\Http\Controllers\PaymentTermController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ClosingEntryController;
use App\Http\Controllers\BudgetRefreshController;
use App\Http\Controllers\MaritalStatusController;
use App\Http\Controllers\NormalBalanceController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\EmployeeStatusController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\InvoiceCategoryController;
use App\Http\Controllers\CashFlowCategoryController;
use App\Http\Controllers\EmployeeIdentityController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\RepaymentCategoryController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\InventoryAdjustController;
use App\Http\Controllers\MaterialSubCategoryController;
use App\Http\Controllers\StockAdjustController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the 'web' middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/change_datetime', [AuthController::class, 'changeDatetime'])->name('change_datetime');
Route::get('/', DashboardController::class)->name('dashboard')->middleware(['auth']);
Route::get('/setup', [SetupController::class, 'index'])->name('setup.index')->middleware(['auth', 'check.permission']);
Route::put('/setup/{id}', [SetupController::class, 'update'])->name('setup.update')->middleware(['auth', 'check.permission']);
Route::resource('/role', RoleController::class)->middleware(['auth', 'check.permission']);
Route::resource('/user', UserController::class)->middleware(['auth', 'check.permission']);
Route::get('/activity_log', ActivityLogController::class)->name('activity_log')->middleware(['auth', 'check.permission']);
Route::resource('/cash_flow_category', CashFlowCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/financial_statement', FinancialStatementController::class)->middleware(['auth', 'check.permission']);
Route::resource('/normal_balance', NormalBalanceController::class)->middleware(['auth', 'check.permission']);
Route::resource('/account_group', AccountGroupController::class)->middleware(['auth', 'check.permission']);
Route::resource('/main_account', MainAccountController::class)->middleware(['auth', 'check.permission']);
Route::resource('/sub_account', SubAccountController::class)->middleware(['auth', 'check.permission']);
Route::resource('/account', AccountController::class)->middleware(['auth', 'check.permission']);
Route::resource('/tax_rate', TaxRateController::class)->middleware(['auth', 'check.permission']);
Route::resource('/journal', JournalController::class)->middleware(['auth', 'check.permission']);
Route::resource('/budget', BudgetController::class)->middleware(['auth', 'check.permission']);
Route::get('/budget_refresh/{budget_id}', BudgetRefreshController::class)->name('budget.refresh')->middleware(['auth', 'check.permission']);
Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index')->middleware(['auth', 'check.permission']);
Route::get('/ledger/data/{account_id}/{start_date}/{end_date}', [LedgerController::class, 'data'])->name('ledger.data');
Route::get('/balance_sheet', [BalanceSheetController::class, 'index'])->name('balance_sheet.index')->middleware(['auth', 'check.permission']);
Route::get('/balance_sheet/data/{year}/{month}', [BalanceSheetController::class, 'data'])->name('balance_sheet.data');
Route::get('/income_statement', [IncomeStatementController::class, 'index'])->name('income_statement.index')->middleware(['auth', 'check.permission']);
Route::get('/income_statement/data/{year}/{month}', [IncomeStatementController::class, 'data'])->name('income_statement.data');
Route::get('/cash_flow', [CashFlowController::class, 'index'])->name('cash_flow.index')->middleware(['auth', 'check.permission']);
Route::get('/cash_flow/data/{year}/{month}', [CashFlowController::class, 'data'])->name('cash_flow.data');
Route::post('/closing_entry', ClosingEntryController::class)->name('closing_entry');
Route::post('/posting', PostingController::class)->name('posting');
Route::resource('/warehouse', WarehouseController::class)->middleware(['auth', 'check.permission']);
Route::resource('/unit', UnitController::class)->middleware(['auth', 'check.permission']);
Route::resource('/material_category', MaterialCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/material_sub_category', MaterialSubCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/material', MaterialController::class)->middleware(['auth', 'check.permission']);
Route::resource('/service', ServiceController::class)->middleware(['auth', 'check.permission']);
Route::resource('/payment_term', PaymentTermController::class)->middleware(['auth', 'check.permission']);
Route::resource('/region', RegionController::class)->middleware(['auth', 'check.permission']);
Route::resource('/business', BusinessController::class)->middleware(['auth', 'check.permission']);
Route::resource('/supplier', SupplierController::class)->middleware(['auth', 'check.permission']);
Route::resource('/customer', CustomerController::class)->middleware(['auth', 'check.permission']);
Route::resource('/vendor', VendorController::class)->middleware(['auth', 'check.permission']);
Route::resource('/invoice_category', InvoiceCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/repayment_category', RepaymentCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/invoice', InvoiceController::class)->middleware(['auth', 'check.permission']);
Route::resource('/repayment', RepaymentController::class)->middleware(['auth', 'check.permission']);
Route::resource('/department', DepartmentController::class)->middleware(['auth', 'check.permission']);
Route::resource('/sub_department', SubDepartmentController::class)->middleware(['auth', 'check.permission']);
Route::resource('/level', LevelController::class)->middleware(['auth', 'check.permission']);
Route::resource('/employee_status', EmployeeStatusController::class)->middleware(['auth', 'check.permission']);
Route::resource('/education', EducationController::class)->middleware(['auth', 'check.permission']);
Route::resource('/campus', CampusController::class)->middleware(['auth', 'check.permission']);
Route::resource('/major', MajorController::class)->middleware(['auth', 'check.permission']);
Route::resource('/religion', ReligionController::class)->middleware(['auth', 'check.permission']);
Route::resource('/marital_status', MaritalStatusController::class)->middleware(['auth', 'check.permission']);
Route::resource('/bank', BankController::class)->middleware(['auth', 'check.permission']);
Route::resource('/skill', SkillController::class)->middleware(['auth', 'check.permission']);
Route::resource('/title', TitleController::class)->middleware(['auth', 'check.permission']);
Route::resource('/employee_identity', EmployeeIdentityController::class)->middleware(['auth', 'check.permission']);
Route::resource('/employee', EmployeeController::class)->middleware(['auth', 'check.permission']);
Route::resource('/shift', ShiftController::class)->middleware(['auth', 'check.permission']);
Route::resource('/attendance', AttendanceController::class)->middleware(['auth', 'check.permission']);
Route::resource('/overtime', OvertimeController::class)->middleware(['auth', 'check.permission']);
Route::resource('/leave', LeaveController::class)->middleware(['auth', 'check.permission']);
Route::resource('/allowance', AllowanceController::class)->middleware(['auth', 'check.permission']);
Route::resource('/deduction', DeductionController::class)->middleware(['auth', 'check.permission']);
Route::resource('/day', DayController::class)->middleware(['auth', 'check.permission']);
Route::resource('/holiday', HolidayController::class)->middleware(['auth', 'check.permission']);
Route::resource('/payroll', PayrollController::class)->middleware(['auth', 'check.permission']);
Route::resource('/stock_adjust', StockAdjustController::class)->middleware(['auth', 'check.permission']);
Route::resource('/inventory_adjust', InventoryAdjustController::class)->middleware(['auth', 'check.permission']);
Route::get('/checklog', [ChecklogController::class, 'index'])->name('checklog.index')->middleware(['auth', 'check.permission']);
Route::get('/checklog/create', [ChecklogController::class, 'create'])->name('checklog.create')->middleware(['auth', 'check.permission']);
Route::post('/checklog', [ChecklogController::class, 'store'])->name('checklog.store')->middleware(['auth', 'check.permission']);


