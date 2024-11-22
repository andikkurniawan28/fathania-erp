<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function inventory_adjust(){
        return $this->belongsTo(InventoryAdjust::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
