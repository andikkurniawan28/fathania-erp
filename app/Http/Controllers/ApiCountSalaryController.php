<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ApiCountSalaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($employee_id, $from, $to)
    {
        $employee = Employee::findOrFail($employee_id);
        return [
            "salary" => Attendance::where('employee_id', $employee_id)->whereBetween('date', [$from, $to])->sum('net_salary'),
            "attendance_credit" => Attendance::where('employee_id', $employee_id)->whereBetween('date', [$from, $to])->sum('credit'),
            "overtime" => Overtime::where('employee_id', $employee_id)->whereBetween('date', [$from, $to])->sum('net_overtime'),
            "overtime_credit" => Overtime::where('employee_id', $employee_id)->whereBetween('date', [$from, $to])->sum('credit'),
            "leave" => Leave::where('employee_id', $employee_id)->whereBetween('to', [$from, $to])->sum('net_leave'),
            "leave_credit" => Leave::where('employee_id', $employee_id)->whereBetween('to', [$from, $to])->sum('credit'),
            "incentive" => $employee->title->incentive,
        ];
    }
}
