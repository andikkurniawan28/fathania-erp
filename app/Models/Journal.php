<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class Journal extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($journal) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Journal '{$journal->id}' was created.",
            ]);
        });

        static::updated(function ($journal) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Journal '{$journal->id}' was updated.",
            ]);
        });

        static::deleted(function ($journal) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Journal '{$journal->id}' was deleted.",
            ]);
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function journal_detail(){
        return $this->hasMany(JournalDetail::class);
    }

    public static function generateID()
    {
        $prefix = 'JRN';
        $date = date('Ymd'); // Format: YYYYMMDD
        $lastJournal = self::whereDate('created_at', today())->latest('created_at')->first();
        $sequence = $lastJournal ? intval(substr($lastJournal->id, -4)) + 1 : 1;
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT); // Ensure 4 digits with leading zeros
        return $prefix . $date . $sequence;
    }
}
