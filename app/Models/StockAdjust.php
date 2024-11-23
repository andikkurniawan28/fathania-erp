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

    public function profit_loss_account(){
        return $this->belongsTo(Account::class, 'profit_loss_account_id');
    }

    protected static function booted()
    {
        static::created(function ($stock_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "StockAdjust '{$stock_adjust->name}' was created.",
            ]);
        });

        static::updated(function ($stock_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "StockAdjust '{$stock_adjust->name}' was updated.",
            ]);
        });

        static::deleted(function ($stock_adjust) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "StockAdjust '{$stock_adjust->name}' was deleted.",
            ]);
        });
    }
}
