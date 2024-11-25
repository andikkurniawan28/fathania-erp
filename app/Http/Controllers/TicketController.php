<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Setup;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Ticket::with('customer', 'user', 'assigned_to')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
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
        return view('ticket.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $customers = Customer::all();
        $users = User::all();
        return view('ticket.create', compact('setup', 'customers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add(["user_id" => auth()->user()->id]);
        $validated = $request->validate([
            "customer_id" => "nullable",
            "user_id" => "required",
            "assigned_to_id" => "required",
            "subject" => "required",
            "description" => "required",
            "priority" => "required",
            "status" => "required",
        ]);
        $ticket = Ticket::create($validated);
        return redirect()->back()->with("success", "Ticket has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $ticket = Ticket::findOrFail($id);
        $customers = Customer::all();
        $users = User::all();
        return view('ticket.edit', compact('setup', 'ticket', 'customers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->request->add(["user_id" => auth()->user()->id]);
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            "customer_id" => "nullable",
            "user_id" => "required",
            "assigned_to_id" => "required",
            "subject" => "required",
            "description" => "required",
            "priority" => "required",
            "status" => "required",
        ]);
        $ticket->update($validated);
        return redirect()->route('ticket.index')->with("success", "Ticket has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Ticket::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Ticket has been deleted");
    }
}
