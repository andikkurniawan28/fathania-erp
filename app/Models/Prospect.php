<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Prospect extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    protected static function booted()
    {
        static::created(function ($prospect) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Prospect '{$prospect->name}' was created.",
            ]);
        });

        static::updated(function ($prospect) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Prospect '{$prospect->name}' was updated.",
            ]);
        });

        static::deleted(function ($prospect) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Prospect '{$prospect->name}' was deleted.",
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
