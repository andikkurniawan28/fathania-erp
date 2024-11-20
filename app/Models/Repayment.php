<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repayment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($repayment) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Repayment '{$repayment->id}' was created.",
            ]);
        });

        static::updated(function ($repayment) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Repayment '{$repayment->id}' was updated.",
            ]);
        });

        static::deleted(function ($repayment) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Repayment '{$repayment->id}' was deleted.",
            ]);
        });
    }

    public function repayment_category(){
        return $this->belongsTo(RepaymentCategory::class);
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

    public function payment_gateway(){
        return $this->belongsTo(Account::class, 'payment_gateway_id');
    }

    public function repayment_detail(){
        return $this->hasMany(RepaymentDetail::class);
    }

    public static function generateID($repayment_category_id)
    {
        $prefix = $repayment_category_id;
        $date = date('Ymd'); // Format: YYYYMMDD
        $lastJournal = self::where("repayment_category_id", $repayment_category_id)
            ->whereDate('created_at', today())
            ->latest('created_at')->first();
        $sequence = $lastJournal ? intval(substr($lastJournal->id, -4)) + 1 : 1;
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT); // Ensure 4 digits with leading zeros
        return $prefix . $date . $sequence;
    }

}
