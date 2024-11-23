<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $setup = Setup::init();
        $data = self::data();
        //return $data;
        return view("dashboard.index", compact('setup', 'data'));
    }

    public static function data(){
        $data["salesMonthly"] = self::salesMonthly();
        $data["revenueVsExpenses"] = self::revenueVsExpenses();
        $data["receivableVsPayable"] = self::receivableVsPayable();
        $data["totalMaterialLoss"] = self::totalMaterialLoss();
        $data["thirdParty"] = self::thirdParty();
        return $data;
    }

    public static function salesMonthly(){
        $salesData = DB::table('invoices')
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->where('invoice_category_id', "SLSM") // Filter kategori penjualan material
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();
        $monthlySales = array_fill(1, 12, 0);
        foreach ($salesData as $sale) {
            $monthlySales[$sale->month] = $sale->total;
        }
        return array_values($monthlySales);
    }

    public static function revenueVsExpenses(){
        // Define account IDs for revenue and expenses
        $revenueAccounts = ['40111', '40211', '70111', '70211', '70311'];
        $expenseAccounts = ['50111', '50211', '50311', '50411', '60111', '60211', '60311', '60312', '80111', '80211', '80311', '80411'];

        // Calculate revenue
        $revenue = DB::table('ledgers')
            ->whereIn('account_id', $revenueAccounts)
            ->selectRaw('COALESCE(SUM(credit), 0) - COALESCE(SUM(debit), 0) as total_revenue')
            ->whereMonth('ledgers.created_at', now()->month)
            ->value('total_revenue');

        // Calculate expenses
        $expenses = DB::table('ledgers')
            ->whereIn('account_id', $expenseAccounts)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as total_expenses')
            ->whereMonth('ledgers.created_at', now()->month)
            ->value('total_expenses');

        // Return the summary as an associative array
        return [
            'revenue' => $revenue,
            'expenses' => $expenses
        ];
    }

    public static function receivableVsPayable(){
        // Calculate receivables
        $receivable = DB::table('receivables')
            ->sum('amount');

        // Calculate payable
        $payable = DB::table('payables')
            ->sum('amount');

        // Return the summary as an associative array
        return [
            'receivable' => $receivable,
            'payable' => $payable
        ];
    }

    public static function totalMaterialLoss()
    {
        $totalLoss = DB::table('inventory_adjusts')
            ->join('stock_adjusts', 'inventory_adjusts.stock_adjust_id', '=', 'stock_adjusts.id')
            ->where('stock_adjusts.stock_normal_balance_id', 'C')
            ->whereMonth('inventory_adjusts.created_at', now()->month)
            ->sum('inventory_adjusts.grand_total');

        return $totalLoss;
    }

    public static function thirdParty(){
        // Calculate suppliers
        $suppliers = DB::table('suppliers')
            ->count('id');

        // Calculate customers
        $customers = DB::table('customers')
            ->count('id');

        // Calculate vendors
        $vendors = DB::table('vendors')
            ->count('id');

        // Return the summary as an associative array
        return [
            'suppliers' => $suppliers,
            'customers' => $customers,
            'vendors' => $vendors,
        ];
    }
}
