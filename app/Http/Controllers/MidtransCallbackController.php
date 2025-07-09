<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class MidtransCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        // ✅ Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = true; // ubah ke false jika development
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ✅ Ambil notifikasi dari Midtrans (bisa dari $request langsung)
        $data = $request->all();

        // Logging awal
        Log::info('Callback diterima dari Midtrans', $data);

        $orderId = $data['order_id'] ?? null;
        $transactionStatus = $data['transaction_status'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;
        $grossAmount = $data['gross_amount'] ?? null;

        if (!$orderId || !$transactionStatus) {
            Log::error('Data Midtrans tidak lengkap', $data);
            return response()->json(['message' => 'Invalid data'], 400);
        }

        // ✅ Validasi Bill
        $bill = Bill::where('order_id', $orderId)->first();

        if (!$bill) {
            Log::warning("Tagihan tidak ditemukan untuk order_id: $orderId");
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

            Log::info("Transaksi berhasil untuk order_id: $orderId");
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $bill->status = 'failed';
            $bill->save();

            Log::info("Transaksi gagal untuk order_id: $orderId - Status: $transactionStatus");
        } else {
            Log::info("Transaksi status tidak ditangani secara eksplisit: $transactionStatus");
        }

        return response()->json(['message' => 'Callback processed'], 200);
    }
}
