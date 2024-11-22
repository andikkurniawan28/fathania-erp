<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjust extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stock_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'stock_normal_balance_id');
    }

    protected static function booted()
    {
        static::created(function ($role) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Role '{$role->name}' was created.",
            ]);
        });

        static::updated(function ($role) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Role '{$role->name}' was updated.",
            ]);
        });

        static::deleted(function ($role) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Role '{$role->name}' was deleted.",
            ]);
        });
    }
}
