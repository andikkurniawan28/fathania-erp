<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prospect(){
        return $this->belongsTo(Prospect::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($activity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Activity '{$activity->name}' was created.",
            ]);
        });

        static::updated(function ($activity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Activity '{$activity->name}' was updated.",
            ]);
        });

        static::deleted(function ($activity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Activity '{$activity->name}' was deleted.",
            ]);
        });
    }
}
