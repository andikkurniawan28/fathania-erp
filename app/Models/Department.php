<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was created.",
            ]);
        });

        static::updated(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was updated.",
            ]);
        });

        static::deleted(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was deleted.",
            ]);
        });
    }
}
