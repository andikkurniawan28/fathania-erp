<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryAdjust extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($inventory_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InventoryAdjust '{$inventory_adjust->id}' was created.",
            ]);
        });

        static::updated(function ($inventory_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InventoryAdjust '{$inventory_adjust->id}' was updated.",
            ]);
        });

        static::deleted(function ($inventory_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InventoryAdjust '{$inventory_adjust->id}' was deleted.",
            ]);
        });
    }

    public function stock_adjust(){
        return $this->belongsTo(StockAdjust::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function inventory_adjust_entry(){
        return $this->hasMany(InventoryAdjustEntry::class);
    }
}
