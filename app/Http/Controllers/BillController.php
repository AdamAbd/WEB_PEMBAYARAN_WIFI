<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bill;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('user');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $periode = $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
            $query->where('bulan', $periode);
        } elseif ($request->filled('bulan')) {
            $query->whereMonth('bulan', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('bulan', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bills = $query->latest()->paginate(10)->withQueryString();
        return view('bills.index', compact('bills'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        $bulanAngka = str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
        $tahun = $request->tahun;
        $periode = "$tahun-$bulanAngka";
        $dueDate = Carbon::createFromFormat('Y-m-d', "$periode-01")->endOfMonth();

        $existing = Bill::where('bulan', $periode)->count();
        if ($existing > 0) {
            return back()->with('warning', "Tagihan untuk bulan $periode sudah pernah dibuat.");
        }

        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            Bill::create([
                'user_id' => $user->id,
                'amount' => 150000,
                'due_date' => $dueDate,
                'bulan' => $periode,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', "Tagihan bulan $periode berhasil dibuat untuk semua pengguna.");
    }

    // ✅ MIDTRANS CHECKOUT
    public function pay($id)
    {
        $bill = Bill::findOrFail($id);

        if ($bill->user_id !== Auth::id()) {
            abort(403);
        }

        if ($bill->status === 'paid') {
            return redirect()->route('user.dashboard')->with('info', 'Tagihan sudah dibayar.');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat order_id unik dan simpan ke database
        $orderId = 'ORDER-' . $bill->id . '-' . time();
        $bill->order_id = $orderId;
        $bill->save();

        // Buat parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $bill->amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        return view('user.checkout', compact('bill', 'snapToken'));
    }
}