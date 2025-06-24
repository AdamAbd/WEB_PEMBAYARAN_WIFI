<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('Y-m');

        $totalUsers = User::where('role', 'user')->count();

        $totalPendapatan = Transaction::where('status', 'paid')
            ->whereHas('bill', function($query) use ($bulanIni) {
                $query->where('bulan', $bulanIni);
            })
            ->sum('amount');

        $userSudahBayar = Transaction::where('status', 'paid')
            ->whereHas('bill', function($query) use ($bulanIni) {
                $query->where('bulan', $bulanIni);
            })
            ->distinct('user_id')
            ->count('user_id');

        $userBelumBayar = $totalUsers - $userSudahBayar;

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPendapatan',
            'userSudahBayar',
            'userBelumBayar'
        ));
    }
}
