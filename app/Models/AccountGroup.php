<?php

namespace App\Models;

use App\Models\Ledger;
use App\Models\SubAccount;
use App\Models\ActivityLog;
use App\Models\MainAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountGroup extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($account_group) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Account Group '{$account_group->name}' was created.",
            ]);
        });

        static::updated(function ($account_group) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Account Group '{$account_group->name}' was updated.",
            ]);
        });

        static::deleted(function ($account_group) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Account Group '{$account_group->name}' was deleted.",
            ]);
        });
    }

    public function financial_statement(){
        return $this->belongsTo(FinancialStatement::class);
    }

    public function normal_balance(){
        return $this->belongsTo(NormalBalance::class);
    }

    public function main_account(){
        return $this->hasMany(MainAccount::class);
    }

    public static function balanceSheet($year, $month, $id, $normal_balance_id){
        $main_account_id = MainAccount::where('account_group_id', $id)->select('id')->get();
        $sub_account_id = SubAccount::whereIn('main_account_id', $main_account_id)->select('id')->get();
        $accounts = Account::whereIn('sub_account_id', $sub_account_id)->get();
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

    public static function incomeStatement($year, $month, $id, $normal_balance_id){
        $main_account_id = MainAccount::where('account_group_id', $id)->select('id')->get();
        $sub_account_id = SubAccount::whereIn('main_account_id', $main_account_id)->select('id')->get();
        $accounts = Account::whereIn('sub_account_id', $sub_account_id)->get();
        $total_balance = 0;

        foreach($accounts as $account)
        {
            $running_balance = Ledger::where('account_id', $account->id)
                                    ->where('is_closing_entry', 0)
                                    ->whereYear('created_at', $year)
                                    ->whereMonth('created_at', $month);

            if($normal_balance_id == "D"){
                // Untuk beban, hitung total debit dan kredit dalam periode tersebut
                $account->balance = $running_balance->sum('debit') - $running_balance->sum('credit');
            } else {
                // Untuk pendapatan, hitung total kredit dan debit dalam periode tersebut
                $account->balance = $running_balance->sum('credit') - $running_balance->sum('debit');
            }

            $total_balance += $account->balance;
        }

        return $total_balance;
    }
}
