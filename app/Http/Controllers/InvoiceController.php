<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Payable;
use App\Models\TaxRate;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Receivable;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceCategory;
use Yajra\DataTables\DataTables;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Invoice::with('invoice_category', 'warehouse', 'supplier', 'customer')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('invoice_category_id', function($row) {
                    return $row->invoice_category ? $row->invoice_category->name : '-'; // Replace invoice_category_id with user name
                })
                ->editColumn('warehouse_id', function($row) {
                    return $row->warehouse ? $row->warehouse->name : '-'; // Replace warehouse_id with warehouse name
                })
                ->editColumn('supplier_id', function($row) {
                    return $row->supplier ? $row->supplier->name : '-'; // Replace supplier_id with supplier name
                })
                ->editColumn('customer_id', function($row) {
                    return $row->customer ? $row->customer->name : '-'; // Replace customer_id with customer name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('invoice.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $invoice_categories = InvoiceCategory::all();
        $payment_terms = PaymentTerm::all();
        $tax_rates = TaxRate::all();
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $customers = Customer::all();
        $materials = Material::all();
        $payment_gateways = Account::where("is_payment_gateway", 1)->get();
        return view('invoice.create', compact('setup', 'invoice_categories', 'payment_terms', 'tax_rates', 'warehouses', 'suppliers', 'customers', 'materials', 'payment_gateways'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice_category = InvoiceCategory::findOrFail($request->invoice_category_id) ;
        $request = self::storeValidate($request);
        try {
            DB::beginTransaction();
            $invoice = self::saveHeader($request);
            self::saveBody($request, 1, $invoice_category);
            self::saveInvoiceToLedger($request, $invoice_category);
            self::savePaymentToLedger($request, $invoice_category);
            self::updatePayableOrReceivable($request, $invoice_category);
            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'Invoice successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $setup = Setup::init();
        $invoice = Invoice::findOrFail($id);
        return view("invoice.show", compact('setup', 'invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $invoice = Invoice::findOrFail($id);
            $invoice->invoice_category->deal_with == "suppliers"
                ? Supplier::decreasePayable($invoice->supplier_id, $invoice->left)
                : Customer::decreaseReceivable($invoice->customer_id, $invoice->left);
            foreach($invoice->invoice_detail as $detail){
                Material::resetStock($detail->material_id, $detail->invoice->warehouse_id, $detail->invoice->invoice_category->stock_normal_balance_id, $detail->qty);
            }
            $invoice->delete();
            DB::commit();
            return redirect()->back()->with("success", "Invoice has been deleted");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public static function storeValidate($request){
        $request->request->add(['user_id' => auth()->id()]);
        $request->validate([
            'id' => 'required|unique:invoices',
            'invoice_category_id' => 'required|exists:invoice_categories,id',
            'user_id' => 'required|exists:users,id',
            'payment_term_id' => 'required|exists:payment_terms,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'valid_until' => 'required|date',
            'subtotal' => 'required|numeric',
            'taxes' => 'required|numeric',
            'freight' => 'required|numeric',
            'discount' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'paid' => 'required|numeric',
            'left' => 'required|numeric',
            'payment_gateway_id' => 'nullable|exists:accounts,id',
            'details' => 'required|array',
            'details.*.material_id' => 'required|exists:materials,id',
            'details.*.qty' => 'required|numeric',
            'details.*.price' => 'required|numeric',
            'details.*.discount' => 'required|numeric',
            'details.*.total' => 'required|numeric',
        ]);
        return $request;
    }

    public static function saveHeader($request){
        $invoice = Invoice::create([
            'id' => $request->id,
            'invoice_category_id' => $request->invoice_category_id,
            'user_id' => $request->user_id,
            'payment_term_id' => $request->payment_term_id,
            'tax_rate_id' => $request->tax_rate_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'customer_id' => $request->customer_id,
            'valid_until' => $request->valid_until,
            'subtotal' => $request->subtotal,
            'taxes' => $request->taxes,
            'freight' => $request->freight,
            'discount' => $request->discount,
            'grand_total' => $request->grand_total,
            'paid' => $request->paid,
            'left' => $request->left,
            'payment_gateway_id' => $request->payment_gateway_id,
        ]);
        return $invoice;
    }

    public static function saveBody($request, $item_order, $invoice_category){
        $stock_normal_balance_id = $invoice_category->stock_normal_balance_id;
        foreach ($request->details as $detail) {
            InvoiceDetail::create([
                'invoice_id' => $request->id,
                'material_id' => $detail['material_id'],
                'item_order' => $item_order,
                'qty' => $detail['qty'],
                'price' => $detail['price'],
                'discount' => $detail['discount'],
                'total' => $detail['total'],
            ]);
            Material::countStock($detail['material_id'], $request->warehouse_id, $stock_normal_balance_id, $detail['qty']);
            InventoryMovement::create([
                'invoice_id' => $request->id,
                'user_id' => $request->user_id,
                'warehouse_id' => $request->warehouse_id,
                'material_id' => $detail['material_id'],
                'in' => ($stock_normal_balance_id == "D") ? $detail['qty'] : 0,
                'out' => ($stock_normal_balance_id == "C") ? $detail['qty'] : 0,
                'description' => "{$invoice_category->name} - {$request->id}",
            ]);
            $item_order++;
        }
    }

    public static function saveInvoiceToLedger($request, $invoice_category){
        $data = [
            [
                "invoice_id" => $request->id,
                "account_id" => $invoice_category->subtotal_account_id,
                "user_id" => $request->user_id,
                "description" => "{$invoice_category->name} - {$request->id}",
                "debit" => $invoice_category->subtotal_normal_balance_id == "D" ? $request->subtotal : 0,
                "credit" => $invoice_category->subtotal_normal_balance_id == "C" ? $request->subtotal : 0,
            ],
            [
                "invoice_id" => $request->id,
                "account_id" => $invoice_category->taxes_account_id,
                "user_id" => $request->user_id,
                "description" => "{$invoice_category->name} - {$request->id}",
                "debit" => $invoice_category->taxes_normal_balance_id == "D" ? $request->taxes : 0,
                "credit" => $invoice_category->taxes_normal_balance_id == "C" ? $request->taxes : 0,
            ],
            [
                "invoice_id" => $request->id,
                "account_id" => $invoice_category->freight_account_id,
                "user_id" => $request->user_id,
                "description" => "{$invoice_category->name} - {$request->id}",
                "debit" => $invoice_category->freight_normal_balance_id == "D" ? $request->freight : 0,
                "credit" => $invoice_category->freight_normal_balance_id == "C" ? $request->freight : 0,
            ],
            [
                "invoice_id" => $request->id,
                "account_id" => $invoice_category->discount_account_id,
                "user_id" => $request->user_id,
                "description" => "{$invoice_category->name} - {$request->id}",
                "debit" => $invoice_category->discount_normal_balance_id == "D" ? $request->discount : 0,
                "credit" => $invoice_category->discount_normal_balance_id == "C" ? $request->discount : 0,
            ],
            [
                "invoice_id" => $request->id,
                "account_id" => $invoice_category->grand_total_account_id,
                "user_id" => $request->user_id,
                "description" => "{$invoice_category->name} - {$request->id}",
                "debit" => $invoice_category->grand_total_normal_balance_id == "D" ? $request->grand_total : 0,
                "credit" => $invoice_category->grand_total_normal_balance_id == "C" ? $request->grand_total : 0,
            ],
        ];

        $filteredData = array_filter($data, function($entry) {
            return $entry['debit'] != 0 || $entry['credit'] != 0;
        });

        Ledger::insert($filteredData);
    }

    public static function savePaymentToLedger($request, $invoice_category){
        if ($request->payment_gateway_id || $request->paid != 0) {
            Ledger::insert([
                [
                    "invoice_id" => $request->id,
                    "account_id" => $invoice_category->grand_total_account_id,
                    "user_id" => $request->user_id,
                    "description" => "Pembayaran {$invoice_category->name} - {$request->id}",
                    "debit" => $invoice_category->grand_total_normal_balance_id == "C" ? $request->paid : 0,
                    "credit" => $invoice_category->grand_total_normal_balance_id == "D" ? $request->paid : 0,
                ],
                [
                    "invoice_id" => $request->id,
                    "account_id" => $request->payment_gateway_id,
                    "user_id" => $request->user_id,
                    "description" => "Pembayaran {$invoice_category->name} - {$request->id}",
                    "debit" => $invoice_category->grand_total_normal_balance_id == "D" ? $request->paid : 0,
                    "credit" => $invoice_category->grand_total_normal_balance_id == "C" ? $request->paid : 0,
                ],
            ]);
        }
    }

    public static function updatePayableOrReceivable($request, $invoice_category)
    {
        if ($request->left > 0) {

            $invoice_category->deal_with == "suppliers"
                ? Supplier::increasePayable($request->supplier_id, $request->left)
                : Customer::increaseReceivable($request->customer_id, $request->left);

            $invoice_category->deal_with == "suppliers"
                ? Payable::create([
                    "description" => "{$invoice_category->name} - {$request->id}",
                    "user_id" => $request->user_id,
                    "invoice_id" => $request->id,
                    "supplier_id" => $request->supplier_id,
                    "amount" => $request->left,
                ])
                : Receivable::create([
                    "description" => "{$invoice_category->name} - {$request->id}",
                    "user_id" => $request->user_id,
                    "invoice_id" => $request->id,
                    "customer_id" => $request->customer_id,
                    "amount" => $request->left,
                ]);
        }
    }

}
