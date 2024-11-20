<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\TimeOperation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checklog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function log($request)
    {
        self::create([
            "employee_id" => $request->employee_id,
            "created_at" => $request->timestamp,
        ]);

        $incomplete_attendance = self::checkIncompleteAttendance($request);

        if ($incomplete_attendance) {
            $interval = self::countInterval($incomplete_attendance->shift->start, $request->time);
            $credit = self::countCredit($interval, $incomplete_attendance->shift->salary_multiplier);
            $net_salary = self::countNetSalary($incomplete_attendance->basic_salary, $credit);
            $early_late = self::countEarlyOrLate($incomplete_attendance->shift->finish, $request->time);
            self::complete($incomplete_attendance, $request->time, $credit, $net_salary, $early_late);
            return redirect()->route('checklog.index')->with("success", "Attendance has been updated");
        } else {
            $attendance = self::checkTodayAttendance($request);
            if (!$attendance) {
                $shift = self::identifyShift($request);
                if ($shift) {
                    $employee = Employee::whereId($request->employee_id)->first();
                    $basic_salary = Setup::get()->last()->daily_wage * $employee->title->salary_multiplier;
                    $early_late = self::countEarlyOrLate($shift->start, $request->time);
                    self::init($request, $basic_salary, $shift, $early_late);
                    return redirect()->route('checklog.index')->with("success", "Attendance has been recorded");
                } else {
                    return redirect()->back()->with("fail", "The time does not fall within any shift. Please come back later.");
                }
            } else {
                self::complete($attendance, $request->time, $credit, $net_salary, $early_late);
                return redirect()->route('checklog.index')->with("success", "Attendance has been updated");
            }
        }
    }

    public static function checkIncompleteAttendance($request)
    {
        $today = $request->date;
        $yesterday = TimeOperation::decreaseDay($today, 1);
        return Attendance::where("employee_id", $request->employee_id)
            ->whereIn("date", [$yesterday, $today])
            ->where("check_out", null)
            ->first();
    }

    public static function identifyShift($request)
    {
        $shifts = Shift::all();
        $currentTime = Carbon::createFromFormat('H:i:s', $request->time);
        $currentShift = null;
        foreach ($shifts as $shift) {
            $shiftStart = Carbon::createFromFormat('H:i:s', $shift->start);
            $startMinusOneHour = $shiftStart->copy()->subHour();
            $startPlusOneHour = $shiftStart->copy()->addHour();
            if ($currentTime->between($startMinusOneHour, $startPlusOneHour)) {
                $currentShift = $shift;
                break;
            }
        }
        return $currentShift;
    }

    public static function countInterval($check_in, $check_out)
    {
        $checkInTime = Carbon::createFromFormat('H:i:s', $check_in);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $check_out);
        return $checkInTime->diffInHours($checkOutTime);
    }

    public static function countCredit($interval, $salary_multiplier)
    {
        $real_credit = ($interval / 8) * 1;
        if($real_credit > 1) $credit = 1; else $credit = $real_credit;
        return $credit * $salary_multiplier;
    }

    public static function countNetSalary($basic_salary, $credit)
    {
        return $basic_salary * $credit;
    }

    public static function countEarlyOrLate($shift, $time)
    {
        $statedTime = Carbon::createFromFormat('H:i:s', $shift);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $time);
        $diff = $statedTime->diffInMinutes($checkOutTime);
        $isLate = $checkOutTime->greaterThan($statedTime);
        $data = [
            "late" => $isLate ? $diff : 0,
            "early" => !$isLate ? $diff : 0,
        ];
        return $data;
    }

    public static function complete($attendance, $time, $credit, $net_salary, $early_late)
    {
        $attendance->update([
            "check_out" => $time,
            "credit" => $credit,
            "net_salary" => $net_salary,
            "early_check_out" => $early_late["early"],
            "late_check_out" => $early_late["late"],
        ]);
    }

    public static function checkTodayAttendance($request)
    {
        $today = $request->date;
        return Attendance::where("employee_id", $request->employee_id)
            ->where("date", $today)
            ->first();
    }

    public static function init($request, $basic_salary, $shift, $early_late)
    {
        Attendance::create([
            "employee_id" => $request->employee_id,
            "date" => $request->date,
            "basic_salary" => $basic_salary,
            "credit" => 0,
            "net_salary" => 0,
            "check_in" => $request->time,
            "shift_id" => $shift->id,
            "early_check_in" => $early_late["early"],
            "late_check_in" => $early_late["late"],
        ]);
    }
}
