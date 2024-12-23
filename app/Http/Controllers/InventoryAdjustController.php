<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\Setup;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\StockAdjust;
use Illuminate\Http\Request;
use App\Models\InventoryAdjust;
use Yajra\DataTables\DataTables;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryAdjustEntry;

class InventoryAdjustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = InventoryAdjust::with('stock_adjust', 'warehouse', 'user')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('stock_adjust_id', function($row) {
                    return $row->stock_adjust ? $row->stock_adjust->name : '-'; // Replace stock_adjust_id with user name
                })
                ->editColumn('warehouse_id', function($row) {
                    return $row->warehouse ? $row->warehouse->name : '-'; // Replace warehouse_id with warehouse name
                })
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : '-'; // Replace user_id with user name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('inventory_adjust.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $stock_adjusts = StockAdjust::all();
        $warehouses = Warehouse::all();
        $materials = Material::all();
        return view('inventory_adjust.create', compact('setup', 'stock_adjusts', 'warehouses', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add([
            'grand_total' => Setup::checkFormat($request->grand_total),
        ]);
        $data = $request->all();
        if (isset($data['details']) && is_array($data['details'])) {
            foreach ($data['details'] as $index => $detail) {
                if (isset($detail['price'])) {
                    $data['details'][$index]['price'] = Setup::checkFormat($detail['price']);
                }
                if (isset($detail['qty'])) {
                    $data['details'][$index]['qty'] = Setup::checkFormat($detail['qty']);
                }
                if (isset($detail['total'])) {
                    $data['details'][$index]['total'] = Setup::checkFormat($detail['total']);
                }
            }
        }
        $request->merge($data);
        $stock_adjust = StockAdjust::findOrFail($request->stock_adjust_id);
        $request = self::storeValidate($request);
        try {
            DB::beginTransaction();
            $inventory_adjust = self::saveHeader($request);
            self::saveBody($inventory_adjust, $request, 1, $stock_adjust);
            DB::commit();
            return redirect()->route('inventory_adjust.index')->with('success', 'InventoryAdjust successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create inventory_adjust: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $setup = Setup::init();
        $inventory_adjust = InventoryAdjust::findOrFail($id);
        return view("inventory_adjust.show", compact('setup', 'inventory_adjust'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryAdjust $inventory_adjust)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryAdjust $inventory_adjust)
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
            $inventory_adjust = InventoryAdjust::findOrFail($id);
            foreach($inventory_adjust->inventory_adjust_entry as $detail){
                Material::resetStock($detail->material_id, $detail->inventory_adjust->warehouse_id, $detail->inventory_adjust->stock_adjust->stock_normal_balance_id, $detail->qty);
            }
            $inventory_adjust->delete();
            DB::commit();
            return redirect()->back()->with("success", "InventoryAdjust has been deleted");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('fail', 'Failed to create inventory_adjust: ' . $e->getMessage());
        }
    }

    public static function storeValidate($request) {
        // Add the authenticated user ID to the request
        $request->request->add(['user_id' => auth()->id()]);

        // Validate the request data
        $request->validate([
            'stock_adjust_id' => 'required|exists:stock_adjusts,id',
            'user_id' => 'required|exists:users,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'grand_total' => 'required|numeric',
            'details' => 'required|array',
            'details.*.material_id' => 'required|exists:materials,id',
            'details.*.qty' => 'required|numeric',
            'details.*.price' => 'required|numeric',
            'details.*.total' => 'required|numeric',
        ]);

        return $request;
    }

    public static function saveHeader($request){
        $inventory_adjust = InventoryAdjust::create([
            'stock_adjust_id' => $request->stock_adjust_id,
            'user_id' => $request->user_id,
            'warehouse_id' => $request->warehouse_id,
            'grand_total' => $request->grand_total,
        ]);
        $material_inventory_account_id = Setup::get()->last()->material_inventory_id;
        $stock_adjust = StockAdjust::whereId($request->stock_adjust_id)->get()->last();
        Ledger::create([
            "inventory_adjust_id" => $inventory_adjust->id,
            "account_id" => $material_inventory_account_id,
            "user_id" => $request->user_id,
            'debit' => ($stock_adjust->stock_normal_balance_id == "D") ? $request->grand_total : 0,
            'credit' => ($stock_adjust->stock_normal_balance_id == "C") ? $request->grand_total : 0,
            "description" => "{$stock_adjust->name} - {$inventory_adjust->id}",
        ]);
        Ledger::create([
            "inventory_adjust_id" => $inventory_adjust->id,
            "account_id" => $stock_adjust->profit_loss_account_id,
            "user_id" => $request->user_id,
            'debit' => ($stock_adjust->stock_normal_balance_id == "C") ? $request->grand_total : 0,
            'credit' => ($stock_adjust->stock_normal_balance_id == "D") ? $request->grand_total : 0,
            "description" => "{$stock_adjust->name} - {$inventory_adjust->id}",
        ]);
        return $inventory_adjust;
    }

    public static function saveBody($inventory_adjust, $request, $item_order, $stock_adjust){
        $stock_normal_balance_id = $stock_adjust->stock_normal_balance_id;
        foreach ($request->details as $detail) {
            $detail['qty'] = Setup::checkFormat($detail['qty']);
            $detail['price'] = Setup::checkFormat($detail['price']);
            $detail['total'] = Setup::checkFormat($detail['total']);
            InventoryAdjustEntry::create([
                'inventory_adjust_id' => $inventory_adjust->id,
                'material_id' => $detail['material_id'],
                'item_order' => $item_order,
                'qty' => $detail['qty'],
                'price' => $detail['price'],
                'total' => $detail['total'],
            ]);
            Material::countStock($detail['material_id'], $request->warehouse_id, $stock_normal_balance_id, $detail['qty']);
            InventoryMovement::create([
                'inventory_adjust_id' => $inventory_adjust->id,
                'user_id' => $request->user_id,
                'warehouse_id' => $request->warehouse_id,
                'material_id' => $detail['material_id'],
                'in' => ($stock_normal_balance_id == "D") ? $detail['qty'] : 0,
                'out' => ($stock_normal_balance_id == "C") ? $detail['qty'] : 0,
                'description' => "{$inventory_adjust->stock_adjust->name} - {$inventory_adjust->id}",
            ]);
            $item_order++;
        }
    }

}
