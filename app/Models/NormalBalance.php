<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class NormalBalance extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($normal_balance) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Normal Balance '{$normal_balance->name}' was created.",
            ]);
        });

        static::updated(function ($normal_balance) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Normal Balance '{$normal_balance->name}' was updated.",
            ]);
        });

        static::deleted(function ($normal_balance) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Normal Balance '{$normal_balance->name}' was deleted.",
            ]);
        });
    }
}
