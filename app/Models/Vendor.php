<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    protected static function booted()
    {
        static::created(function ($vendor) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Vendor '{$vendor->name}' was created.",
            ]);
        });

        static::updated(function ($vendor) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Vendor '{$vendor->name}' was updated.",
            ]);
        });

        static::deleted(function ($vendor) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Vendor '{$vendor->name}' was deleted.",
            ]);
        });
    }

    public static function increasePayable($id, $total){
        $last = self::whereId($id)->get()->last()->payable ?? 0;
        $current = $last + $total;
        self::whereId($id)->update(["payable" => $current]);
    }

    public static function decreasePayable($id, $total){
        $last = self::whereId($id)->get()->last()->payable ?? 0;
        $current = $last - $total;
        self::whereId($id)->update(["payable" => $current]);
    }
}
