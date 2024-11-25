<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    protected static function booted()
    {
        static::created(function ($ticket) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Ticket '{$ticket->id}' was created.",
            ]);
        });

        static::updated(function ($ticket) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Ticket '{$ticket->id}' was updated.",
            ]);
        });

        static::deleted(function ($ticket) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Ticket '{$ticket->id}' was deleted.",
            ]);
        });
    }
}
