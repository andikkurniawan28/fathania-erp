<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeOperation extends Model
{
    use HasFactory;

    public static function decreaseDay($date, $day)
    {
        return date("Y-m-d", strtotime("$date -{$day} day"));
    }

    public static function diffInHour($check_in, $check_out)
    {
        $check_in_time = Carbon::createFromFormat('H:i:s', $check_in);
        $check_out_time = Carbon::createFromFormat('H:i:s', $check_out);
        return $check_in_time->diffInHours($check_out_time);
    }

    public static function diffInDay($request)
    {
        $from_date = Carbon::createFromFormat('Y-m-d', $request->from);
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to);
        return $to_date->diffInDays($from_date);
    }
}
