<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Setup;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Repayment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\RepaymentDetail;
use Yajra\DataTables\DataTables;
use App\Models\RepaymentCategory;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Repayment::with('repayment_category', 'supplier', 'customer')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('repayment_category_id', function($row) {
                    return $row->repayment_category ? $row->repayment_category->name : '-'; // Replace repayment_category_id with user name
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
        return view('repayment.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $repayment_categories = RepaymentCategory::all();
        $suppliers = Supplier::all();
        $customers = Customer::all();
        $payment_gateways = Account::where("is_payment_gateway", 1)->get();
        return view('repayment.create', compact('setup', 'repayment_categories', 'suppliers', 'customers', 'payment_gateways'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $repayment_category = RepaymentCategory::findOrFail($request->repayment_category_id) ;
        $request = self::storeValidate($request);
        try {
            DB::beginTransaction();
            $repayment = self::saveHeader($request, $repayment_category);
            self::saveBody($request, 1, $repayment_category);
            self::saveRepaymentToLedger($request, $repayment_category);
            self::updatePayableOrReceivable($request, $repayment_category);
            DB::commit();
            return redirect()->route('repayment.index')->with('success', 'Repayment successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create repayment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $setup = Setup::init();
        $repayment = Repayment::findOrFail($id);
        return view("repayment.show", compact('setup', 'repayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repayment $repayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repayment $repayment)
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
            $repayment = Repayment::findOrFail($id);
            foreach($repayment->repayment_detail as $detail){
                $invoice = Invoice::findOrFail($detail->invoice_id);
                $left = $invoice->left + $detail->total;
                $repayment->repayment_category->deal_with == "suppliers"
                    ? Supplier::increasePayable($repayment->supplier_id, $detail->total)
                    : Customer::increaseReceivable($repayment->customer_id, $detail->total);
                $invoice->update(["left" => $left]);
            }
            $repayment->delete();
            DB::commit();
            return redirect()->back()->with("success", "Repayment has been deleted");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create repayment: ' . $e->getMessage());
        }
    }

    public static function storeValidate($request){
        $request->request->add(['user_id' => auth()->id()]);
        $request->validate([
            'id' => 'required|unique:repayments',
            'repayment_category_id' => 'required|exists:repayment_categories,id',
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'grand_total' => 'required|numeric',
            'payment_gateway_id' => 'required|exists:accounts,id',
            'details' => 'required|array',
            'details.*.invoice_id' => 'required|exists:invoices,id',
            'details.*.left' => 'required|numeric',
            'details.*.discount' => 'required|numeric',
            'details.*.total' => 'required|numeric',
        ]);
        return $request;
    }

    public static function saveHeader($request, $repayment_category){
        $repayment = Repayment::create([
            'id' => $request->id,
            'repayment_category_id' => $request->repayment_category_id,
            'user_id' => $request->user_id,
            'supplier_id' => $request->supplier_id,
            'customer_id' => $request->customer_id,
            'grand_total' => $request->grand_total,
            'payment_gateway_id' => $request->payment_gateway_id,
        ]);
        Ledger::insert([
            "repayment_id" => $request->id,
            "account_id" => $request->payment_gateway_id,
            "user_id" => $request->user_id,
            "description" => "{$repayment_category->name} - {$request->id}",
            "debit" => $repayment_category->grand_total_normal_balance_id == "C" ? $request->grand_total : 0,
            "credit" => $repayment_category->grand_total_normal_balance_id == "D" ? $request->grand_total : 0,
        ]);
        return $repayment;
    }

    public static function saveBody($request, $item_order, $repayment_category){
        foreach ($request->details as $detail) {
            RepaymentDetail::create([
                'repayment_id' => $request->id,
                'invoice_id' => $detail['invoice_id'],
                'left' => $detail['left'],
                'discount' => $detail['discount'],
                'total' => $detail['total'],
            ]);
            Invoice::whereId($detail['invoice_id'])->update([
                "left" => $detail['left'] - $detail['total'],
            ]);
        }
    }

    public static function saveRepaymentToLedger($request, $repayment_category){
        $data = [
            [
                "repayment_id" => $request->id,
                "account_id" => $repayment_category->grand_total_account_id,
                "user_id" => $request->user_id,
                "description" => "{$repayment_category->name} - {$request->id}",
                "debit" => $repayment_category->grand_total_normal_balance_id == "D" ? $request->grand_total : 0,
                "credit" => $repayment_category->grand_total_normal_balance_id == "C" ? $request->grand_total : 0,
            ],
        ];

        $filteredData = array_filter($data, function($entry) {
            return $entry['debit'] != 0 || $entry['credit'] != 0;
        });

        Ledger::insert($filteredData);
    }

    public static function updatePayableOrReceivable($request, $repayment_category)
    {
        $repayment_category->deal_with == "suppliers"
            ? Supplier::decreasePayable($request->supplier_id, $request->grand_total)
            : Customer::decreaseReceivable($request->customer_id, $request->grand_total);
    }

}
