<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\Opportunity;
use App\Models\OpportunityStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Opportunity::with('prospect', 'customer', 'user', 'opportunity_status')->latest()->get();
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
                ->editColumn('opportunity_status_id', function($row) {
                    return $row->opportunity_status ? $row->opportunity_status->name : '-';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('opportunity.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $prospects = Prospect::all();
        $customers = Customer::all();
        $opportunity_statuses = OpportunityStatus::all();
        return view('opportunity.create', compact('setup', 'prospects', 'customers', 'opportunity_statuses'));
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
            "title" => "required",
            "opportunity_status_id" => "required",
            "value" => "required",
        ]);
        $opportunity = Opportunity::create($validated);
        return redirect()->back()->with("success", "Opportunity has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Opportunity $opportunity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $opportunity = Opportunity::findOrFail($id);
        $prospects = Prospect::all();
        $customers = Customer::all();
        $opportunity_statuses = OpportunityStatus::all();
        return view('opportunity.edit', compact('setup', 'opportunity', 'prospects', 'customers', 'opportunity_statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);
        $validated = $request->validate([
            "prospect_id" => "nullable",
            "customer_id" => "nullable",
            "title" => "required",
            "opportunity_status_id" => "required",
            "value" => "required",
        ]);
        $opportunity->update($validated);
        return redirect()->route('opportunity.index')->with("success", "Opportunity has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Opportunity::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Opportunity has been deleted");
    }
}
