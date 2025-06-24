<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Log untuk debugging (jangan di production!)
        Log::info('Midtrans Callback Payload:', $payload);

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Misalnya order_id = bill-15 → ambil ID tagihan
        $billId = (int) str_replace('bill-', '', $orderId);

        $bill = Bill::find($billId);
        if (!$bill) {
            return response()->json(['message' => 'Bill not found'], 404);
        }

        // Update berdasarkan status dari Midtrans
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            $bill->status = 'paid';
            $bill->save();

            Transaction::updateOrCreate(
                ['bill_id' => $bill->id],
                [
                    'user_id' => $bill->user_id,
                    'amount' => $bill->amount,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
