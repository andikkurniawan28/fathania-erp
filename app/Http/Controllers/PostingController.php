<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\Journal;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        self::resetLedger($request);
        self::pullJournal($request);
        self::pullTransaction($request);
        return redirect()->back()->with('success', 'All data has been posted successfully.');
    }

    public static function resetLedger($request){
        Ledger::whereBetween('created_at', [$request->start_date, $request->end_date])
            ->delete();
    }

    public static function pullJournal($request){
        $journals = Journal::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
        foreach($journals as $journal){
            foreach($journal->journal_detail as $journal_detail){
                // Ini Penting !!
                if(strpos($journal_detail->description, "Closing") !== false){
                    $is_closing_entry = 1;
                } else {
                    $is_closing_entry = 0;
                }
                Ledger::create([
                    'journal_id' => $journal->id,
                    'account_id' => $journal_detail->account_id,
                    'description' => "{$journal->id} - {$journal_detail->description}",
                    'debit' => $journal_detail->debit,
                    'credit' => $journal_detail->credit,
                    'user_id' => auth()->id(),
                    'is_closing_entry' => $is_closing_entry,
                ]);
            }
        }
    }

    public static function pullTransaction($request){
        $transactions = Transaction::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
        foreach($transactions as $transaction){
            // Simpan data Transaksi ke Buku Besar
            $data = [
                [
                    "transaction_id" => $transaction->id,
                    "account_id" => $transaction->transaction_category->subtotal_account_id,
                    "user_id" => $transaction->user_id,
                    "description" => "{$transaction->transaction_category->name} - {$transaction->id}",
                    "debit" => $transaction->transaction_category->subtotal_normal_balance_id == "D" ? $transaction->subtotal : 0,
                    "credit" => $transaction->transaction_category->subtotal_normal_balance_id == "C" ? $transaction->subtotal : 0,
                ],
                [
                    "transaction_id" => $transaction->id,
                    "account_id" => $transaction->transaction_category->taxes_account_id,
                    "user_id" => $transaction->user_id,
                    "description" => "{$transaction->transaction_category->name} - {$transaction->id}",
                    "debit" => $transaction->transaction_category->taxes_normal_balance_id == "D" ? $transaction->taxes : 0,
                    "credit" => $transaction->transaction_category->taxes_normal_balance_id == "C" ? $transaction->taxes : 0,
                ],
                [
                    "transaction_id" => $transaction->id,
                    "account_id" => $transaction->transaction_category->freight_account_id,
                    "user_id" => $transaction->user_id,
                    "description" => "{$transaction->transaction_category->name} - {$transaction->id}",
                    "debit" => $transaction->transaction_category->freight_normal_balance_id == "D" ? $transaction->freight : 0,
                    "credit" => $transaction->transaction_category->freight_normal_balance_id == "C" ? $transaction->freight : 0,
                ],
                [
                    "transaction_id" => $transaction->id,
                    "account_id" => $transaction->transaction_category->discount_account_id,
                    "user_id" => $transaction->user_id,
                    "description" => "{$transaction->transaction_category->name} - {$transaction->id}",
                    "debit" => $transaction->transaction_category->discount_normal_balance_id == "D" ? $transaction->discount : 0,
                    "credit" => $transaction->transaction_category->discount_normal_balance_id == "C" ? $transaction->discount : 0,
                ],
                [
                    "transaction_id" => $transaction->id,
                    "account_id" => $transaction->transaction_category->grand_total_account_id,
                    "user_id" => $transaction->user_id,
                    "description" => "{$transaction->transaction_category->name} - {$transaction->id}",
                    "debit" => $transaction->transaction_category->grand_total_normal_balance_id == "D" ? $transaction->grand_total : 0,
                    "credit" => $transaction->transaction_category->grand_total_normal_balance_id == "C" ? $transaction->grand_total : 0,
                ],
            ];

            // Filter untuk menghapus array dengan nilai debit dan credit 0
            $filteredData = array_filter($data, function($entry) {
                return $entry['debit'] != 0 || $entry['credit'] != 0;
            });

            // Insert ke dalam database
            Ledger::insert($filteredData);

            // Simpan data pelunasan jika ada pelunasan
            if($request->payment_gateway_id != null){
                Ledger::insert([
                    [
                        "transaction_id" => $transaction->id,
                        "account_id" => $transaction->transaction_category->grand_total_account_id,
                        "user_id" => $transaction->user_id,
                        "description" => "Pembayaran {$transaction->transaction_category->name} - {$transaction->id}",
                        "debit" => $transaction->transaction_category->grand_total_normal_balance_id == "C" ? $transaction->paid : 0,
                        "credit" => $transaction->transaction_category->grand_total_normal_balance_id == "D" ? $transaction->paid : 0,
                    ],
                    [
                        "transaction_id" => $transaction->id,
                        "account_id" => $transaction->payment_gateway_id,
                        "user_id" => $transaction->user_id,
                        "description" => "Pembayaran {$transaction->transaction_category->name} - {$transaction->id}",
                        "debit" => $transaction->transaction_category->grand_total_normal_balance_id == "D" ? $transaction->paid : 0,
                        "credit" => $transaction->transaction_category->grand_total_normal_balance_id == "C" ? $transaction->paid : 0,
                    ],
                ]);
            }
        }
    }
}
