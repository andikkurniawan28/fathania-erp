<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($currency) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Currency '{$currency->name}' was created.",
            ]);
        });

        static::updated(function ($currency) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Currency '{$currency->name}' was updated.",
            ]);
        });

        static::deleted(function ($currency) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Currency '{$currency->name}' was deleted.",
            ]);
        });
    }
}
