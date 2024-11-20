<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($shift) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Shift '{$shift->name}' was created.",
            ]);
        });

        static::updated(function ($shift) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Shift '{$shift->name}' was updated.",
            ]);
        });

        static::deleted(function ($shift) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Shift '{$shift->name}' was deleted.",
            ]);
        });
    }
}
