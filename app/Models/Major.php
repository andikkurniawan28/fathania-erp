<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Major extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($major) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Major '{$major->name}' was created.",
            ]);
        });

        static::updated(function ($major) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Major '{$major->name}' was updated.",
            ]);
        });

        static::deleted(function ($major) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Major '{$major->name}' was deleted.",
            ]);
        });
    }
}
