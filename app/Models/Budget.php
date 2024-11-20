<?php

namespace App\Models;

use App\Models\Ledger;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($budget) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Budget '{$budget->name}' was created.",
            ]);
        });

        static::updated(function ($budget) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Budget '{$budget->name}' was updated.",
            ]);
        });

        static::deleted(function ($budget) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Budget '{$budget->name}' was deleted.",
            ]);
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public static function countSpent($budget_id, $start_date, $end_date){
        $account = Budget::whereId($budget_id)->get()->first()->account;

        $normal_balance = $account->sub_account->main_account->account_group->normal_balance_id;

        $data = Ledger::where('account_id', $account->id)
            ->whereBetween('created_at', [$start_date, $end_date]);

        $debit = $data->sum('debit');
        $credit = $data->sum('credit');

        if($normal_balance == "D"){
            $spent = $debit - $credit;
        } else {
            $spent = $credit - $debit;
        }

        return $spent;
    }

    public static function countRemaining($amount, $spent){
        return $amount - $spent;
    }

    public static function nonActive($budget_id){
        self::whereId($budget_id)->update(["is_active" => 0]);
    }
}
