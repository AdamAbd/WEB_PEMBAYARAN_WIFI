<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class RiwayatController extends Controller
{
    public function index()
{
    $transactions = Transaction::with('bill') // tanpa 'user'
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

    return view('user.riwayat', compact('transactions'));
}

}
