<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Checklog;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use App\Models\TimeOperation;
use Yajra\DataTables\DataTables;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Leave::with('employee')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', function($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->make(true);
        }
        return view('leave.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $employees = Employee::all();
        return view('leave.create', compact('setup', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "employee_id" => "required",
            "from" => "required",
            "to" => "required",
        ]);
        $credit = TimeOperation::diffInDay($request);
        $remaining_leave = self::checkRemaining($request);
        $employee = Employee::findOrFail($request->employee_id);
        if($credit < $remaining_leave) {
            $daily_wage = Setup::get()->last()->daily_wage;
            $basic_leave = $daily_wage * $employee->title->salary_multiplier;
            $net_leave = $basic_leave * $credit;
            $new_leave = $remaining_leave - $credit;
            $request->request->add([
                "credit" => $credit,
                "basic_leave" => $basic_leave,
                "net_leave" => $net_leave,
            ]);
            Leave::create($request->all());
            $employee->update(["leave" => $new_leave]);
            return redirect()->route('leave.index')->with("success", "Leave has been recorded");
        } else {
            return redirect()->route('leave.index')->with("fail", "{$employee->name} leave credit is {$employee->leave}");
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        self::restore($leave);
        $leave->delete();
        return redirect()->back()->with("success", "Leave has been deleted");
    }

    public static function checkRemaining($request)
    {
        return Employee::whereId($request->employee_id)
            ->get()->last()->leave;
    }

    public static function restore($leave)
    {
        $last_leave = Employee::whereId($leave->employee_id)->get()->last()->leave;
        $new_leave = $last_leave + $leave->credit;
        Employee::whereId($leave->employee_id)->update(["leave" => $new_leave]);
    }


}
