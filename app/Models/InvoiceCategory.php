<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceCategory extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function stock_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'stock_normal_balance_id');
    }

    public function subtotal_account(){
        return $this->belongsTo(Account::class, 'subtotal_account_id');
    }

    public function subtotal_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'subtotal_normal_balance_id');
    }

    public function taxes_account(){
        return $this->belongsTo(Account::class, 'taxes_account_id');
    }

    public function taxes_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'taxes_normal_balance_id');
    }

    public function freight_account(){
        return $this->belongsTo(Account::class, 'freight_account_id');
    }

    public function freight_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'freight_normal_balance_id');
    }

    public function discount_account(){
        return $this->belongsTo(Account::class, 'discount_account_id');
    }

    public function discount_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'discount_normal_balance_id');
    }

    public function grand_total_account(){
        return $this->belongsTo(Account::class, 'grand_total_account_id');
    }

    public function grand_total_normal_balance(){
        return $this->belongsTo(NormalBalance::class, 'grand_total_normal_balance_id');
    }

    protected static function booted()
    {
        static::created(function ($invoice_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InvoiceCategory '{$invoice_category->name}' was created.",
            ]);
        });

        static::updated(function ($invoice_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InvoiceCategory '{$invoice_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($invoice_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "InvoiceCategory '{$invoice_category->name}' was deleted.",
            ]);
        });
    }

}
