<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Day extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($day) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Day '{$day->name}' was created.",
            ]);
        });

        static::updated(function ($day) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Day '{$day->name}' was updated.",
            ]);
        });

        static::deleted(function ($day) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Day '{$day->name}' was deleted.",
            ]);
        });
    }
}
