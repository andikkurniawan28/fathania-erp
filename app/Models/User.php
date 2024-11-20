<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyusername;
use App\Models\ActivityLog;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'username',
        'password',
        'is_active',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->name}' was created.",
            ]);
        });

        static::updated(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->name}' was updated.",
            ]);
        });

        static::deleted(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->name}' was deleted.",
            ]);
        });
    }
}
