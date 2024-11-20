<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class SubDepartment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    protected static function booted()
    {
        static::created(function ($sub_department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Department '{$sub_department->name}' was created.",
            ]);
        });

        static::updated(function ($sub_department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Department '{$sub_department->name}' was updated.",
            ]);
        });

        static::deleted(function ($sub_department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Department '{$sub_department->name}' was deleted.",
            ]);
        });
    }
}
