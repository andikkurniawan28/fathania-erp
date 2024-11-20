<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\Checklog;
use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Models\TimeOperation;
use Yajra\DataTables\DataTables;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Overtime::with('employee')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', function($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->make(true);
        }
        return view('overtime.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $employees = Employee::all();
        return view('overtime.create', compact('setup', 'employees'));
    }

    public function store(Request $request)
    {
        $overtime_is_valid = self::checkValidity($request);
        if($overtime_is_valid != null){
            $credit = TimeOperation::diffInHour($request->check_in, $request->check_out);
            $hourly_overtime = Setup::hourlyOvertime();
            $net_overtime = $hourly_overtime * $credit;
            Overtime::create([
                "date" => $request->date,
                "employee_id" => $request->employee_id,
                "check_in" => $request->check_in,
                "check_out" => $request->check_out,
                "credit" => $credit,
                "hourly_overtime" => $hourly_overtime,
                "net_overtime" => $net_overtime,
            ]);
            return redirect()->route('overtime.index')->with("success", "Overtime has been recorded");
        } else {
            return redirect()->route('overtime.index')->with("fail", "Overtime is invalid");
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Overtime $overtime)
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
        Overtime::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Overtime has been deleted");
    }

    public static function checkValidity($request)
    {
        return Checklog::where('employee_id', $request->employee_id)
            ->whereBetween('created_at', [$request->date." ".$request->check_in, $request->date." ".$request->check_out])
            ->get() ?? null;
    }
}
