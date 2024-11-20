<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($business) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Business '{$business->name}' was created.",
            ]);
        });

        static::updated(function ($business) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Business '{$business->name}' was updated.",
            ]);
        });

        static::deleted(function ($business) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Business '{$business->name}' was deleted.",
            ]);
        });
    }
}
