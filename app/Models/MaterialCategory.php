<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($material_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Category '{$material_category->name}' was created.",
            ]);
        });

        static::updated(function ($material_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Category '{$material_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($material_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material Category '{$material_category->name}' was deleted.",
            ]);
        });
    }
}
