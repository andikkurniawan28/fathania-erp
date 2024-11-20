<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Level extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($level) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Level '{$level->name}' was created.",
            ]);
        });

        static::updated(function ($level) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Level '{$level->name}' was updated.",
            ]);
        });

        static::deleted(function ($level) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Level '{$level->name}' was deleted.",
            ]);
        });
    }
}
