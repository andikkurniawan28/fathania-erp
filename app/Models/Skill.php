<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($skill) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Skill '{$skill->name}' was created.",
            ]);
        });

        static::updated(function ($skill) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Skill '{$skill->name}' was updated.",
            ]);
        });

        static::deleted(function ($skill) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Skill '{$skill->name}' was deleted.",
            ]);
        });
    }
}
