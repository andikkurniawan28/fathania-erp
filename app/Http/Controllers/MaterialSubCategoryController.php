<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use App\Models\MaterialCategory;
use App\Models\MaterialSubCategory;

class MaterialSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $material_sub_categories = MaterialSubCategory::all();
        return view('material_sub_category.index', compact('setup', 'material_sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $material_categories = MaterialCategory::all();
        return view('material_sub_category.create', compact('setup', 'material_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:material_sub_categories",
            "material_category_id" => "required",
        ]);
        $material_sub_category = MaterialSubCategory::create($validated);
        return redirect()->back()->with("success", "MaterialSubCategory has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialSubCategory $material_sub_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $material_sub_category = MaterialSubCategory::findOrFail($id);
        $material_categories = MaterialCategory::all();
        return view('material_sub_category.edit', compact('setup', 'material_sub_category', 'material_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material_sub_category = MaterialSubCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:material_sub_categories,name,' . $material_sub_category->id,
            "material_category_id" => "required",
        ]);
        $material_sub_category->update($validated);
        return redirect()->route('material_sub_category.index')->with("success", "MaterialSubCategory has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MaterialSubCategory::findOrFail($id)->delete();
        return redirect()->back()->with("success", "MaterialSubCategory has been deleted");
    }
}
