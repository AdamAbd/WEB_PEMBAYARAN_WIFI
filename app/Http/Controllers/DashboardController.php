<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bill;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // Total user dengan role user
        $totalUsers = User::where('role', 'user')->count();

        // Total pendapatan bulan ini
        $totalPendapatan = Transaction::where('status', 'paid')
            ->whereHas('bill', function ($query) use ($bulanIni) {
                $query->where('bulan', $bulanIni);
            })
            ->sum('amount');

        // Jumlah user yang sudah bayar bulan ini
        $userSudahBayar = Transaction::where('status', 'paid')
            ->whereHas('bill', function ($query) use ($bulanIni) {
                $query->where('bulan', $bulanIni);
            })
            ->distinct('user_id')
            ->count('user_id');

        // Jumlah user yang punya tagihan bulan ini tapi belum bayar
        $userBelumBayar = Bill::where('bulan', $bulanIni)
            ->whereDoesntHave('transaction', function ($query) {
                $query->where('status', 'paid');
            })
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPendapatan',
            'userSudahBayar',
            'userBelumBayar'
        ));
    }
}
