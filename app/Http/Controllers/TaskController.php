<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Setup;
use App\Models\Customer;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Task::with('prospect', 'customer', 'user', 'assigned_to')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('prospect_id', function($row) {
                    return $row->prospect ? $row->prospect->name : '-'; // Replace prospect_id with user name
                })
                ->editColumn('customer_id', function($row) {
                    return $row->customer ? $row->customer->name : '-'; // Replace customer_id with user name
                })
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : '-'; // Replace user_id with user name
                })
                ->editColumn('assigned_to_id', function($row) {
                    return $row->assigned_to ? $row->assigned_to->name : '-'; // Replace assigned_to_id with assigned_to name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('task.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $prospects = Prospect::all();
        $customers = Customer::all();
        $users = User::all();
        return view('task.create', compact('setup', 'prospects', 'customers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add(["user_id" => auth()->user()->id]);
        $validated = $request->validate([
            "prospect_id" => "nullable",
            "customer_id" => "nullable",
            "user_id" => "required",
            "assigned_to_id" => "required",
            "title" => "required",
            "description" => "required",
            "status" => "required",
            "due_date" => "required",
        ]);
        $task = Task::create($validated);
        return redirect()->back()->with("success", "Task has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $task = Task::findOrFail($id);
        $prospects = Prospect::all();
        $customers = Customer::all();
        $users = User::all();
        return view('task.edit', compact('setup', 'task', 'prospects', 'customers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $validated = $request->validate([
            "prospect_id" => "nullable",
            "customer_id" => "nullable",
            "user_id" => "required",
            "assigned_to_id" => "required",
            "title" => "required",
            "description" => "required",
            "status" => "required",
            "due_date" => "required",
        ]);
        $task->update($validated);
        return redirect()->route('task.index')->with("success", "Task has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Task has been deleted");
    }
}
