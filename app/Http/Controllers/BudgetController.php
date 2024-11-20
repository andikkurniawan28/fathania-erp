<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Budget;
use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Budget::with('user', 'account')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : 'N/A'; // Replace user_id with user name
                })
                ->editColumn('account_id', function($row) {
                    return $row->account ? $row->account->name : 'N/A'; // Replace account_id with account name
                })
                ->editColumn('updated_at', function($row) {
                    return $row->updated_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('budget.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $accounts = Account::all();
        return view('budget.create', compact('setup', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add([
            "user_id" => auth()->id(),
            "spent" => 0,
            "remaining" => $request->amount,
        ]);
        $validated = $request->validate([
            "name" => "required|unique:budgets",
            "account_id" => "required",
            "user_id" => "required",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "amount" => "required",
            "spent" => "required",
            "remaining" => "required",
        ]);
        $budget = Budget::create($validated);
        return redirect()->back()->with("success", "Budget has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $budget = Budget::findOrFail($id);
        $accounts = Account::all();
        return view('budget.edit', compact('setup', 'budget', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);
        $request->request->add([
            "remaining" => $request->amount - $budget->spent,
        ]);
        $validated = $request->validate([
            'name' => 'required|unique:budgets,name,' . $budget->id,
            "account_id" => "required",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "amount" => "required",
            "remaining" => "required",
        ]);
        $budget->update($validated);
        return redirect()->route('budget.index')->with("success", "Budget has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Budget::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Budget has been deleted");
    }
}
