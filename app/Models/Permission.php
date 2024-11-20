<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
