<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class ApiGenerateMaterialInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($material_id)
    {
        $material = Material::with('unit')->whereId($material_id)->get()->last();
        $material->cost_price = $material->buy_price;
        return response()->json(['material' => $material]);
    }
}
