<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerm;
use Illuminate\Http\Request;

class ApiGenerateValidUntilController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($payment_term_id, $current_date)
    {
        // Ambil hari dari payment term berdasarkan ID
        $paymentTerm = PaymentTerm::find($payment_term_id);

        if (!$paymentTerm) {
            return response()->json(['error' => 'Payment term not found'], 404);
        }

        $day = $paymentTerm->day;

        // Konversi $current_date menjadi objek Carbon
        $currentDate = \Carbon\Carbon::parse($current_date);

        // Tambahkan hari ke tanggal saat ini
        $valid_until = $currentDate->addDays($day)->format('Y-m-d');

        return response()->json(['valid_until' => $valid_until]);
    }
}
