<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Warehouse '{$warehouse->name}' was created.",
            ]);
            $column_name = str_replace(' ', '_', $warehouse->name);
            $queries = [
                "ALTER TABLE materials ADD COLUMN `{$column_name}` FLOAT NULL",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });

        static::updated(function ($warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Warehouse '{$warehouse->name}' was updated.",
            ]);
        });

        static::deleted(function ($warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Warehouse '{$warehouse->name}' was deleted.",
            ]);
            $column_name = str_replace(' ', '_', $warehouse->name);
            $queries = [
                "ALTER TABLE materials DROP COLUMN `{$column_name}`",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });
    }
}
