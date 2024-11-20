<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class MaritalStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($marital_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Marital Status '{$marital_status->name}' was created.",
            ]);
        });

        static::updated(function ($marital_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Marital Status '{$marital_status->name}' was updated.",
            ]);
        });

        static::deleted(function ($marital_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Marital Status '{$marital_status->name}' was deleted.",
            ]);
        });
    }
}
