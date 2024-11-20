<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($campus) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Campus '{$campus->name}' was created.",
            ]);
        });

        static::updated(function ($campus) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Campus '{$campus->name}' was updated.",
            ]);
        });

        static::deleted(function ($campus) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Campus '{$campus->name}' was deleted.",
            ]);
        });
    }
}
