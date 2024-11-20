<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class RepaymentCategory extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($repayment_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "RepaymentCategory '{$repayment_category->name}' was created.",
            ]);
        });

        static::updated(function ($repayment_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "RepaymentCategory '{$repayment_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($repayment_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "RepaymentCategory '{$repayment_category->name}' was deleted.",
            ]);
        });
    }

    public function grand_total_account(){
        return $this->belongsTo(Account::class, 'grand_total_account_id');
    }

    public function grand_total_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'grand_total_normal_balance_id');
    }
}
