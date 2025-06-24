<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bill;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil riwayat transaksi
        $transactions = Transaction::where('user_id', $user->id)->get();

        // Ambil tagihan yang belum dibayar
        $unpaidBills = Bill::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('due_date', 'desc')
            ->get();

        return view('user.dashboard', compact('user', 'transactions', 'unpaidBills'));
    }
}
