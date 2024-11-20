<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class Title extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sub_department(){
        return $this->belongsTo(SubDepartment::class);
    }

    public function level(){
        return $this->belongsTo(Level::class);
    }

    protected static function booted()
    {
        static::created(function ($title) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Title '{$title->name}' was created.",
            ]);
        });

        static::updated(function ($title) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Title '{$title->name}' was updated.",
            ]);
        });

        static::deleted(function ($title) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Title '{$title->name}' was deleted.",
            ]);
        });
    }
}
