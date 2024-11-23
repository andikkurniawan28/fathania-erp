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

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function repayment(){
        return $this->belongsTo(Repayment::class);
    }
}
