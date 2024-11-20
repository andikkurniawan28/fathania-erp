<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Holiday extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($holiday) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Holiday '{$holiday->name}' was created.",
            ]);
        });

        static::updated(function ($holiday) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Holiday '{$holiday->name}' was updated.",
            ]);
        });

        static::deleted(function ($holiday) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Holiday '{$holiday->name}' was deleted.",
            ]);
        });
    }
}
