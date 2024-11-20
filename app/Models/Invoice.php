<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class Invoice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($invoice) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Invoice '{$invoice->id}' was created.",
            ]);
        });

        static::updated(function ($invoice) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Invoice '{$invoice->id}' was updated.",
            ]);
        });

        static::deleted(function ($invoice) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Invoice '{$invoice->id}' was deleted.",
            ]);
        });
    }

    public function invoice_category(){
        return $this->belongsTo(InvoiceCategory::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payment_term(){
        return $this->belongsTo(PaymentTerm::class);
    }

    public function tax_rate(){
        return $this->belongsTo(TaxRate::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function payment_gateway(){
        return $this->belongsTo(Account::class, 'payment_gateway_id');
    }

    public function invoice_detail(){
        return $this->hasMany(InvoiceDetail::class);
    }

    public static function generateID($invoice_category_id)
    {
        $prefix = $invoice_category_id;
        $date = date('Ymd'); // Format: YYYYMMDD
        $lastJournal = self::where("invoice_category_id", $invoice_category_id)
            ->whereDate('created_at', today())
            ->latest('created_at')->first();
        $sequence = $lastJournal ? intval(substr($lastJournal->id, -4)) + 1 : 1;
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT); // Ensure 4 digits with leading zeros
        return $prefix . $date . $sequence;
    }
}
