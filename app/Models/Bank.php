<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($bank) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bank '{$bank->name}' was created.",
            ]);
        });

        static::updated(function ($bank) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bank '{$bank->name}' was updated.",
            ]);
        });

        static::deleted(function ($bank) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bank '{$bank->name}' was deleted.",
            ]);
        });
    }
}
