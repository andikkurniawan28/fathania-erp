<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class SubAccount extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($sub_account) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Account '{$sub_account->name}' was created.",
            ]);
        });

        static::updated(function ($sub_account) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Account '{$sub_account->name}' was updated.",
            ]);
        });

        static::deleted(function ($sub_account) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Sub Account '{$sub_account->name}' was deleted.",
            ]);
        });
    }

    public function main_account(){
        return $this->belongsTo(MainAccount::class);
    }

    public function account(){
        return $this->hasMany(Account::class);
    }

    public static function balanceSheet($year, $month, $id, $normal_balance_id){
        $accounts = Account::where('sub_account_id', $id)->get();
        $total_balance = 0;
        foreach($accounts as $account)
        {
            $initial_balance = $account->initial_balance;
            $running_balance = Ledger::where('account_id', $account->id)
                                ->whereYear('created_at', $year)
                                ->whereMonth('created_at', $month);

            if($normal_balance_id == "D"){
                $account->balance = $initial_balance + ($running_balance->sum('debit') - $running_balance->sum('credit'));
            } else {
                $account->balance = $initial_balance + ($running_balance->sum('credit') - $running_balance->sum('debit'));
            }
            $total_balance += $account->balance;
        }
        return $total_balance;
    }

    public static function incomeStatement($year, $month, $id, $normal_balance_id)
    {
        $accounts = Account::where('sub_account_id', $id)->get();
        $total_balance = 0;

        foreach($accounts as $account)
        {
            $initial_balance = $account->initial_balance;
            $running_balance = Ledger::where('account_id', $account->id)
                                    ->where('is_closing_entry', 0)
                                    ->whereYear('created_at', $year)
                                    ->whereMonth('created_at', $month);

            if($normal_balance_id == "D"){
                // Untuk pendapatan (Income) biasanya ditambahkan pada debit
                $account->balance = $initial_balance + ($running_balance->sum('debit') - $running_balance->sum('credit'));
            } else {
                // Untuk beban (Expense) biasanya ditambahkan pada kredit
                $account->balance = $initial_balance + ($running_balance->sum('credit') - $running_balance->sum('debit'));
            }

            $total_balance += $account->balance;
        }

        return $total_balance;
    }

}
