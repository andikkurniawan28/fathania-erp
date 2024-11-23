<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function inventory_adjust(){
        return $this->belongsTo(InventoryAdjust::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }

    public function warehouse(){
        return $this->belongsTo(warehouse::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
