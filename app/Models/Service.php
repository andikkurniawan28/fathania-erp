<?php

namespace App\Models;

use App\Models\Warehouse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($service) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Service '{$service->name}' was created.",
            ]);
        });

        static::updated(function ($service) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Service '{$service->name}' was updated.",
            ]);
        });

        static::deleted(function ($service) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Service '{$service->name}' was deleted.",
            ]);
        });
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
