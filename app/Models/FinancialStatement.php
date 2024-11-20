<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class FinancialStatement extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($financial_statement) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Financial Statement '{$financial_statement->name}' was created.",
            ]);
        });

        static::updated(function ($financial_statement) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Financial Statement '{$financial_statement->name}' was updated.",
            ]);
        });

        static::deleted(function ($financial_statement) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Financial Statement '{$financial_statement->name}' was deleted.",
            ]);
        });
    }
}
