<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Callback Payload:', $payload);

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        // ✅ Ganti pencarian bill berdasarkan order_id
        $bill = Bill::where('order_id', $orderId)->first();

        if (!$bill) {
            Log::warning("Bill not found for order_id: $orderId");
            return response()->json(['message' => 'Bill not found'], 404);
        }

        // ✅ Update status berdasarkan status transaksi dari Midtrans
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
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
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $bill->status = 'failed';
            $bill->save();
        }

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
