<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class CashFlowCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($cash_flow_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Cash Flow Category '{$cash_flow_category->name}' was created.",
            ]);
        });

        static::updated(function ($cash_flow_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Cash Flow Category '{$cash_flow_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($cash_flow_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Cash Flow Category '{$cash_flow_category->name}' was deleted.",
            ]);
        });
    }

    public function account(){
        return $this->hasMany(Account::class);
    }
}
