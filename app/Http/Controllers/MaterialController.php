<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\Material;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\MaterialSubCategory;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $materials = Material::with(['material_sub_category.material_category', 'unit'])->get();
            return DataTables::of($materials)
                ->editColumn('material_sub_category_id', function($row) {
                    return $row->material_sub_category ? $row->material_sub_category->name : '-'; // Replace material_sub_category_id with material_sub_category name
                })
                ->editColumn('unit_id', function($row) {
                    return $row->unit ? $row->unit->symbol : '-'; // Replace unit_id with unit name
                })
                ->addColumn('manage', function($row) {
                    return '<div class="btn-group" role="group" aria-label="manage">
                                <a href="'.route('material.edit', $row->id).'" class="btn btn-secondary btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'" data-name="'.$row->name.'">Delete</button>
                            </div>';
                })
                ->rawColumns(['manage'])
                ->make(true);
        }
        $warehouses = Warehouse::all();
        return view('material.index', compact('warehouses', 'setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $material_sub_categories = MaterialSubCategory::all();
        $units = Unit::all();
        return view('material.create', compact('setup', 'material_sub_categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:materials",
            "material_sub_category_id" => "required",
            "unit_id" => "required",
            "sell_price" => "nullable",
            "buy_price" => "nullable",
        ]);
        $material = Material::create($validated);
        return redirect()->back()->with("success", "Material has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $material = Material::findOrFail($id);
        $material_sub_categories = MaterialSubCategory::all();
        $units = Unit::all();
        return view('material.edit', compact('setup', 'material', 'material_sub_categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:materials,name,' . $material->id,
            "material_sub_category_id" => "required",
            "unit_id" => "required",
            "sell_price" => "nullable",
            "buy_price" => "nullable",
        ]);
        $material->update($validated);
        return redirect()->route('material.index')->with("success", "Material has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Material has been deleted");
    }
}
