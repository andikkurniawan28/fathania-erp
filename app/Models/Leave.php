<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::created(function ($leave) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Leave '{$leave->employee->name}' was created.",
            ]);
        });

        static::updated(function ($leave) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Leave '{$leave->employee->name}' was updated.",
            ]);
        });

        static::deleted(function ($leave) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Leave '{$leave->employee->name}' was deleted.",
            ]);
        });
    }
}
