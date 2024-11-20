<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Overtime extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::created(function ($overtime) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Overtime '{$overtime->employee->name}' was created.",
            ]);
        });

        static::updated(function ($overtime) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Overtime '{$overtime->employee->name}' was updated.",
            ]);
        });

        static::deleted(function ($overtime) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Overtime '{$overtime->employee->name}' was deleted.",
            ]);
        });
    }
}
