<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class TaxRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($tax_rate) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Tax Rate '{$tax_rate->name}' was created.",
            ]);
        });

        static::updated(function ($tax_rate) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Tax Rate '{$tax_rate->name}' was updated.",
            ]);
        });

        static::deleted(function ($tax_rate) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Tax Rate '{$tax_rate->name}' was deleted.",
            ]);
        });
    }
}
