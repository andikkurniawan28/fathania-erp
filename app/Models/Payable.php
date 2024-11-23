<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function repayment(){
        return $this->belongsTo(Repayment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
}
