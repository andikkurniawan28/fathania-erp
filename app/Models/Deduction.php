<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deduction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($deduction) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Deduction '{$deduction->name}' was created.",
            ]);
            $column_name = str_replace(' ', '_', $deduction->name);
            $queries = [
                "ALTER TABLE payrolls ADD COLUMN `{$column_name}` DOUBLE NULL",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });

        static::updated(function ($deduction) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Deduction '{$deduction->name}' was updated.",
            ]);
        });

        static::deleted(function ($deduction) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Deduction '{$deduction->name}' was deleted.",
            ]);
            $column_name = str_replace(' ', '_', $deduction->name);
            $queries = [
                "ALTER TABLE payrolls DROP COLUMN `{$column_name}`",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });
    }
}
