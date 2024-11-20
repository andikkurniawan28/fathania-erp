<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function journal(){
        return $this->belongsTo(Journal::class);
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function repayment(){
        return $this->belongsTo(Repayment::class);
    }
}
