<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\Service;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ServiceSubCategory;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $services = Service::with(['unit'])->get();
            return DataTables::of($services)
                ->editColumn('unit_id', function($row) {
                    return $row->unit ? $row->unit->symbol : '-'; // Replace unit_id with unit name
                })
                ->addColumn('manage', function($row) {
                    return '<div class="btn-group" role="group" aria-label="manage">
                                <a href="'.route('service.edit', $row->id).'" class="btn btn-secondary btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'" data-name="'.$row->name.'">Delete</button>
                            </div>';
                })
                ->rawColumns(['manage'])
                ->make(true);
        }
        return view('service.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $units = Unit::all();
        return view('service.create', compact('setup', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "unit_id" => "required",
            "name" => "required|unique:services",
            "sell_price" => "nullable",
            "buy_price" => "nullable",
        ]);
        $service = Service::create($validated);
        return redirect()->back()->with("success", "Service has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $service = Service::findOrFail($id);
        $units = Unit::all();
        return view('service.edit', compact('setup', 'service', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $validated = $request->validate([
            "unit_id" => "required",
            'name' => 'required|unique:services,name,' . $service->id,
            "sell_price" => "nullable",
            "buy_price" => "nullable",
        ]);
        $service->update($validated);
        return redirect()->route('service.index')->with("success", "Service has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Service has been deleted");
    }
}
