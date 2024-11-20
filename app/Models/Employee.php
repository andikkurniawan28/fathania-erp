<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function title(){
        return $this->belongsTo(Title::class);
    }

    public function employee_status(){
        return $this->belongsTo(EmployeeStatus::class);
    }

    public function education(){
        return $this->belongsTo(Education::class);
    }

    public function campus(){
        return $this->belongsTo(Campus::class);
    }

    public function major(){
        return $this->belongsTo(Major::class);
    }

    public function religion(){
        return $this->belongsTo(Religion::class);
    }

    public function marital_status(){
        return $this->belongsTo(MaritalStatus::class);
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    public function employee_skill(){
        return $this->hasMany(EmployeeSkill::class);
    }

    protected static function booted()
    {
        static::created(function ($employee) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee '{$employee->name}' was created.",
            ]);
        });

        static::updated(function ($employee) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee '{$employee->name}' was updated.",
            ]);
        });

        static::deleted(function ($employee) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Employee '{$employee->name}' was deleted.",
            ]);
        });
    }
}
