<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Religion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($religion) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Religion '{$religion->name}' was created.",
            ]);
        });

        static::updated(function ($religion) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Religion '{$religion->name}' was updated.",
            ]);
        });

        static::deleted(function ($religion) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Religion '{$religion->name}' was deleted.",
            ]);
        });
    }
}
