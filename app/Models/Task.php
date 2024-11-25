<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
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

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    protected static function booted()
    {
        static::created(function ($task) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Task '{$task->title}' was created.",
            ]);
        });

        static::updated(function ($task) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Task '{$task->title}' was updated.",
            ]);
        });

        static::deleted(function ($task) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Task '{$task->title}' was deleted.",
            ]);
        });
    }
}
