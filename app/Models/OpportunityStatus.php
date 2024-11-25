<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class OpportunityStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($opportunity_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "OpportunityStatus '{$opportunity_status->name}' was created.",
            ]);
        });

        static::updated(function ($opportunity_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "OpportunityStatus '{$opportunity_status->name}' was updated.",
            ]);
        });

        static::deleted(function ($opportunity_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "OpportunityStatus '{$opportunity_status->name}' was deleted.",
            ]);
        });
    }
}
