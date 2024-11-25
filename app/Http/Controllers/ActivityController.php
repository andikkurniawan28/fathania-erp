<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\Activity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Activity::with('prospect', 'customer', 'user')->latest()->get();
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
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('activity.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $prospects = Prospect::all();
        $customers = Customer::all();
        return view('activity.create', compact('setup', 'prospects', 'customers'));
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
            "subject" => "required",
            "type" => "required",
            "description" => "required",
        ]);
        $activity = Activity::create($validated);
        return redirect()->back()->with("success", "Activity has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $activity = Activity::findOrFail($id);
        $prospects = Prospect::all();
        $customers = Customer::all();
        return view('activity.edit', compact('setup', 'activity', 'prospects', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $validated = $request->validate([
            "prospect_id" => "nullable",
            "customer_id" => "nullable",
            "user_id" => "required",
            "subject" => "required",
            "type" => "required",
            "description" => "required",
        ]);
        $activity->update($validated);
        return redirect()->route('activity.index')->with("success", "Activity has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Activity has been deleted");
    }
}
