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

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Attendance::with('employee')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', function($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->editColumn('shift_id', function($row) {
                    return $row->shift ? $row->shift->name : '-';
                })
                ->make(true);
        }
        return view('attendance.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $employees = Employee::all();
        $shifts = Shift::all();
        return view('attendance.create', compact('setup', 'employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $attendance = Attendance::where('employee_id', $request->employee_id)->where('date', $request->date)->first();
        if($attendance){
            return redirect()->route('attendance.create')->with("fail", "Failed to save, the employee already has attendance recorded for the selected date.");
        } else {
            Attendance::create($request->all());
            return redirect()->route('attendance.create')->with("success", "Attendance has been recorded");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
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
        Attendance::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Attendance has been deleted");
    }
}
