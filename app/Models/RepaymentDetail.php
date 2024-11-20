<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function repayment(){
        return $this->belongsTo(Repayment::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
