<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($region) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Region '{$region->name}' was created.",
            ]);
        });

        static::updated(function ($region) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Region '{$region->name}' was updated.",
            ]);
        });

        static::deleted(function ($region) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Region '{$region->name}' was deleted.",
            ]);
        });
    }
}
