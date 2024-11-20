<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($employee_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Status '{$employee_status->name}' was created.",
            ]);
        });

        static::updated(function ($employee_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Status '{$employee_status->name}' was updated.",
            ]);
        });

        static::deleted(function ($employee_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Status '{$employee_status->name}' was deleted.",
            ]);
        });
    }
}
