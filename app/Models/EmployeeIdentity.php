<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeIdentity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($employee_identity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Identity '{$employee_identity->name}' was created.",
            ]);
            $column_name = str_replace(' ', '_', $employee_identity->name);
            $queries = [
                "ALTER TABLE employees ADD COLUMN `{$column_name}` VARCHAR(255) NULL",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });

        static::updated(function ($employee_identity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Identity '{$employee_identity->name}' was updated.",
            ]);
        });

        static::deleted(function ($employee_identity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee Identity '{$employee_identity->name}' was deleted.",
            ]);
            $column_name = str_replace(' ', '_', $employee_identity->name);
            $queries = [
                "ALTER TABLE employees DROP COLUMN `{$column_name}`",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });
    }
}
