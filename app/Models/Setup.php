<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function init()
    {
        $setup = self::get()->last();
        $setup->permission = Permission::where("role_id", Auth()->user()->role_id)->with('feature')->get();
        return $setup;
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function retained_earning()
    {
        return $this->belongsTo(Account::class, 'retained_earning_id');
    }

    public function material_inventory()
    {
        return $this->belongsTo(Account::class, 'material_inventory_id');
    }

    public static function dailyWage()
    {
        return self::latest()->first()->daily_wage;
    }

    public static function hourlyOvertime()
    {
        return self::latest()->first()->hourly_overtime;
    }

    public static function checkFormat($input){
        $setup = self::get()->last();
        $thousand_separator = $setup->currency->thousand_separator;
        $decimal_separator = $setup->currency->decimal_separator;
        $input = str_replace($thousand_separator, '', $input);
        $input = str_replace($decimal_separator, '.', $input);
        return $input;
    }
}
