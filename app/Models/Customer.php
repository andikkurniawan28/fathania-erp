<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    protected static function booted()
    {
        static::created(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was created.",
            ]);
        });

        static::updated(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was updated.",
            ]);
        });

        static::deleted(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was deleted.",
            ]);
        });
    }

    public static function increaseReceivable($id, $total){
        $last = self::whereId($id)->get()->last()->receivable ?? 0;
        $current = $last + $total;
        self::whereId($id)->update(["receivable" => $current]);
    }

    public static function decreaseReceivable($id, $total){
        $last = self::whereId($id)->get()->last()->receivable ?? 0;
        $current = $last - $total;
        self::whereId($id)->update(["receivable" => $current]);
    }
}
