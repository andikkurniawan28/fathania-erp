<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Shift;
use App\Models\Checklog;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\TimeOperation;
use Yajra\DataTables\DataTables;

class ChecklogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Checklog::with('employee')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', function($row) {
                    return $row->employee ? $row->employee->name : 'N/A'; // Replace employee_id with employee name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        $setup = Setup::init();
        return view('checklog.index', compact('setup'));
    }

    public function create()
    {
        $setup = Setup::init();
        $employees = Employee::all();
        return view('checklog.create', compact('setup', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "employee_id" => "required",
        ]);

        // For Production
        // $timestamp = date("Y-m-d H:i:s");

        // For Testing
        $timestamp = $request->date. " ". $request->time;

        // Add to Request
        $request->request->add(["timestamp" => $timestamp]);
        $formatted_timestamp = date("Y-m-d H", strtotime($timestamp));

        // Mengecek apakah ada record yang sesuai di tabel checklogs
        $checklog_exists = Checklog::where('employee_id', $request->employee_id)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H') = ?", [$formatted_timestamp])
            ->exists();

        if(!$checklog_exists) {
            return Checklog::log($request);
        } else {
            return redirect()->route('checklog.create')->with("fail", "Checklog fail to recorded");
        }
    }

    public static function checkAttendance($request)
    {
        $incomplete_attendance = self::checkIncompleteAttendance($request);
        $shift = self::identifyShift($request);
        if ($incomplete_attendance) {
            $interval = self::countInterval($incomplete_attendance->shift->start, $request->time);
            $credit = self::countCredit($interval, $incomplete_attendance->shift->salary_multiplier);
            $net_salary = self::countNetSalary($incomplete_attendance->basic_salary, $credit);
            $early_late = self::countEarlyOrLate($incomplete_attendance->shift->finish, $request->time);
            self::complete($incomplete_attendance, $request->time, $credit, $net_salary, $early_late);
        } else {
            if ($shift) {
                $today_attendance = self::checkTodayAttendance($request);
                if (!$today_attendance) {
                    $early_late = self::countEarlyOrLate($shift->start, $request->time);
                    self::init($request, Setup::get()->last()->daily_wage * Employee::whereId($request->employee_id)->get()->last()->title->salary_multiplier, $shift, $early_late);
                } else {
                    return redirect()->route('attendance.index')->with("fail", "You are has been recorded for today. Come back tomorrow.");
                }
            } else {
                return redirect()->route('attendance.index')->with("fail", "The time does not fall within any shift. Please come back later.");
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
}
