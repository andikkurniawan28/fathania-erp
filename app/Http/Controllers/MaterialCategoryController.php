<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use App\Models\Setup;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $material_categories = MaterialCategory::all();
        return view('material_category.index', compact('setup', 'material_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('material_category.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:material_categories",
        ]);
        $material_category = MaterialCategory::create($validated);
        return redirect()->back()->with("success", "MaterialCategory has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialCategory $material_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $material_category = MaterialCategory::findOrFail($id);
        return view('material_category.edit', compact('setup', 'material_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material_category = MaterialCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:material_categories,name,' . $material_category->id,
        ]);
        $material_category->update($validated);
        return redirect()->route('material_category.index')->with("success", "MaterialCategory has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MaterialCategory::findOrFail($id)->delete();
        return redirect()->back()->with("success", "MaterialCategory has been deleted");
    }
}
