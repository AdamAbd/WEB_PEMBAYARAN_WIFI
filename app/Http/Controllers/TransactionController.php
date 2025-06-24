<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'bill'])
                            ->latest()
                            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }
    public function userIndex()
{
    $transactions = Transaction::where('user_id', auth()->id())
                        ->latest()
                        ->get();

    return view('transactions.index', compact('transactions'));
}

}
