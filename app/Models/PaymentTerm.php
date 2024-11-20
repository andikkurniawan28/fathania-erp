<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentTerm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($payment_term) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "PaymentTerm '{$payment_term->name}' was created.",
            ]);
        });

        static::updated(function ($payment_term) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "PaymentTerm '{$payment_term->name}' was updated.",
            ]);
        });

        static::deleted(function ($payment_term) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "PaymentTerm '{$payment_term->name}' was deleted.",
            ]);
        });
    }
}
