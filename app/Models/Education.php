<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Education extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($education) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Education '{$education->name}' was created.",
            ]);
        });

        static::updated(function ($education) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Education '{$education->name}' was updated.",
            ]);
        });

        static::deleted(function ($education) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Education '{$education->name}' was deleted.",
            ]);
        });
    }
}
