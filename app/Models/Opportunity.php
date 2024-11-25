<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends Model
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

    public function opportunity_status(){
        return $this->belongsTo(OpportunityStatus::class);
    }

    protected static function booted()
    {
        static::created(function ($opportunity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Opportunity '{$opportunity->id}' was created.",
            ]);
        });

        static::updated(function ($opportunity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Opportunity '{$opportunity->id}' was updated.",
            ]);
        });

        static::deleted(function ($opportunity) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Opportunity '{$opportunity->id}' was deleted.",
            ]);
        });
    }
}
