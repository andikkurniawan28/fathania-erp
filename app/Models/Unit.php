<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Unit '{$unit->name}' was created.",
            ]);
        });

        static::updated(function ($unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Unit '{$unit->name}' was updated.",
            ]);
        });

        static::deleted(function ($unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Unit '{$unit->name}' was deleted.",
            ]);
        });
    }
}
