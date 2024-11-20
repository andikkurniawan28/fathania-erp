<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialSubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function material_category(){
        return $this->belongsTo(MaterialCategory::class);
    }

    protected static function booted()
    {
        static::created(function ($material_sub_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Sub Category '{$material_sub_category->name}' was created.",
            ]);
        });

        static::updated(function ($material_sub_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Sub Category '{$material_sub_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($material_sub_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Sub Category '{$material_sub_category->name}' was deleted.",
            ]);
        });
    }
}
