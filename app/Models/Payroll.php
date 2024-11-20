<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::created(function ($payroll) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Payroll '{$payroll->employee->name}' was created.",
            ]);
        });

        static::updated(function ($payroll) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Payroll '{$payroll->employee->name}' was updated.",
            ]);
        });

        static::deleted(function ($payroll) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Payroll '{$payroll->employee->name}' was deleted.",
            ]);
        });
    }
}
