<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Setup;
use App\Models\Payroll;
use App\Models\Checklog;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\TimeOperation;
use Yajra\DataTables\DataTables;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Payroll::with('employee')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', function($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->make(true);
        }
        return view('payroll.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $employees = Employee::all();
        $allowances = Allowance::all();
        $deductions = Deduction::all();
        return view('payroll.create', compact('setup', 'employees', 'allowances', 'deductions'));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);
        $request->request->add([
            "year" => date("Y", strtotime($request->month)),
            "month" => date("F", strtotime($request->month)),
        ]);
        $payroll_exist = Payroll::where('month', $request->month)->where('year', $request->year)->first();
        if(!$payroll_exist){
            Payroll::create($request->all());
            return redirect()->back()->with("success", "Payroll has been recorded");
        }
        return redirect()->back()->with("fail", "Payroll has been recorded for today. Come back tomorrow.");
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $setup = Setup::init();
        $payroll = Payroll::findOrFail($id);
        $allowances = Allowance::all();
        $deductions = Deduction::all();
        return view('payroll.show', compact('setup', 'payroll', 'allowances', 'deductions'));
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
        Payroll::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Payroll has been deleted");
    }
}
