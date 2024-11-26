<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Budget;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Journal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JournalDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    // Index method to display list of journals
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Journal::with('user')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : 'N/A'; // Replace user_id with user name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        return view('journal.index', compact('setup'));
    }

    // Create method to show create journal form
    public function create()
    {
        $setup = Setup::init();
        $accounts = Account::all();
        $id = Journal::generateID();
        return view('journal.create', compact('setup', 'accounts', 'id'));
    }

    // Store method to save new journal and its details
    public function store(Request $request)
    {
        // Ambil semua data dari request
        $data = $request->all();

        // Format setiap elemen debit dan credit di dalam details
        if (isset($data['details']) && is_array($data['details'])) {
            foreach ($data['details'] as $index => $detail) {
                if (isset($detail['debit'])) {
                    $data['details'][$index]['debit'] = Setup::checkFormat($detail['debit']);
                }
                if (isset($detail['credit'])) {
                    $data['details'][$index]['credit'] = Setup::checkFormat($detail['credit']);
                }
            }
        }

        // Mutasi ulang data ke dalam request
        $request->merge($data);

        $request->validate([
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit' => 'nullable|numeric',
            'details.*.credit' => 'nullable|numeric',
            'details.*.description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $journalId = $request->id;

            $totalDebit = collect($request->details)->sum('debit');
            $totalCredit = collect($request->details)->sum('credit');

            $journal = Journal::create([
                'id' => $journalId,
                'user_id' => auth()->id(),
                'debit' => $totalDebit,
                'credit' => $totalCredit,
            ]);

            foreach ($request->details as $key => $detail) {
                JournalDetail::create([
                    'journal_id' => $journalId,
                    'account_id' => $detail['account_id'],
                    'description' => $detail['description'],
                    'item_order' => $key + 1,
                    'debit' => $detail['debit'] ?? 0,
                    'credit' => $detail['credit'] ?? 0,
                ]);
                Ledger::create([
                    'journal_id' => $journalId,
                    'account_id' => $detail['account_id'],
                    'description' => "{$journalId} - {$detail['description']}",
                    'debit' => $detail['debit'] ?? 0,
                    'credit' => $detail['credit'] ?? 0,
                    'user_id' => auth()->id(),
                ]);

                // Refresh Budget
                $budget = Budget::where('account_id', $detail['account_id'])
                    ->where('is_active', 1)
                    ->get()
                    ->first() ?? null;
                if($budget != null){
                    $spent = Budget::countSpent($budget->id, $budget->start_date, $budget->end_date);
                    $remaining = $budget->amount - $spent;
                    Budget::whereId($budget->id)->update([
                        "spent" => $spent,
                        "remaining" => $remaining,
                    ]);
                }
            }
        });

        return redirect()->route('journal.index')->with('success', 'Journal created successfully.');
    }

    // Show method to display a single journal
    public function show($id)
    {
        $setup = Setup::init();
        $journal = Journal::with('journal_detail')->findOrFail($id);
        return view('journal.show2', compact('setup', 'journal'));
    }

    // Edit method to show the edit form for a journal
    public function edit($id)
    {
        $setup = Setup::init();
        $journal = Journal::with('journal_detail')->findOrFail($id);
        $accounts = Account::all();
        return view('journal.edit', compact('setup', 'journal', 'accounts'));
    }

    // Update method to save changes to a journal
    public function update(Request $request, $id)
    {
        $request->validate([
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit' => 'nullable|numeric',
            'details.*.credit' => 'nullable|numeric',
            'details.*.description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $id) {
            $journal = Journal::findOrFail($id);

            $totalDebit = collect($request->details)->sum('debit');
            $totalCredit = collect($request->details)->sum('credit');

            $journal->update([
                'debit' => $totalDebit,
                'credit' => $totalCredit,
            ]);

            $journal->journal_detail()->delete();
            Ledger::where('journal_id', $id)->delete();

            foreach ($request->details as $key => $detail) {
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $detail['account_id'],
                    'description' => $detail['description'],
                    'item_order' => $key + 1,
                    'debit' => $detail['debit'] ?? 0,
                    'credit' => $detail['credit'] ?? 0,
                ]);
                Ledger::create([
                    'journal_id' => $journal->id,
                    'account_id' => $detail['account_id'],
                    'description' => "{$journal->id} - {$detail['description']}",
                    'debit' => $detail['debit'] ?? 0,
                    'credit' => $detail['credit'] ?? 0,
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('journal.index')->with('success', 'Journal updated successfully.');
    }

    // Destroy method to delete a journal
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $journal = Journal::findOrFail($id);
            $journal->journal_detail()->delete();
            Ledger::where('journal_id', $id)->delete();
            $journal->delete();
        });

        return redirect()->route('journal.index')->with('success', 'Journal deleted successfully.');
    }
}
