<?php

namespace App\Models;

use App\Models\Warehouse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was created.",
            ]);
        });

        static::updated(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was updated.",
            ]);
        });

        static::deleted(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was deleted.",
            ]);
        });
    }

    public function material_sub_category(){
        return $this->belongsTo(MaterialSubCategory::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public static function countStock($material_id, $warehouse_id, $stock_normal_balance_id, $qty){
        $warehouse_name = str_replace(' ', '_', Warehouse::whereId($warehouse_id)->get()->last()->name);
        $last_balance = self::whereId($material_id)->get()->last()->{$warehouse_name} ?? 0;
        if($stock_normal_balance_id == "D"){
            $current_balance = $last_balance + $qty;
        } else {
            $current_balance = $last_balance - $qty;
        }
        Material::whereId($material_id)->update([
            "{$warehouse_name}" => $current_balance,
        ]);
    }

    public static function resetStock($material_id, $warehouse_id, $stock_normal_balance_id, $qty){
        $warehouse_name = str_replace(' ', '_', Warehouse::whereId($warehouse_id)->get()->last()->name);
        $last_balance = self::whereId($material_id)->get()->last()->{$warehouse_name} ?? 0;
        if($stock_normal_balance_id == "C"){
            $current_balance = $last_balance + $qty;
        } else {
            $current_balance = $last_balance - $qty;
        }
        Material::whereId($material_id)->update([
            "{$warehouse_name}" => $current_balance,
        ]);
    }
}
