<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        // ✅ Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey'); // ambil dari config
        Config::$isProduction = true; // ganti ke false kalau testing
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ✅ Ambil notifikasi dari Midtrans (otomatis validasi & parsing)
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        Log::info('Midtrans Callback Received', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        // ✅ Cari Bill berdasarkan order_id
        $bill = Bill::where('order_id', $orderId)->first();

        if (!$bill) {
            Log::warning("Bill not found for order_id: $orderId");
            return response()->json(['message' => 'Bill not found'], 404);
        }

        // ✅ Proses status transaksi
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

            Log::info("Pembayaran berhasil untuk order_id: $orderId");
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $bill->status = 'failed';
            $bill->save();

            Log::info("Pembayaran gagal untuk order_id: $orderId - Status: $transactionStatus");
        }

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
