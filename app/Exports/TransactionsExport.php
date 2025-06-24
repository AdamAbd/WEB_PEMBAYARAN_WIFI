<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::with(['user', 'bill'])->latest()->get();
    }

    public function map($transaction): array
    {
        return [
            $transaction->user->name,
            $transaction->bill->bulan ?? '-',
            $transaction->amount,
            ucfirst($transaction->status),
            $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('d-m-Y H:i') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama User',
            'Bulan Tagihan',
            'Jumlah',
            'Status',
            'Tanggal Bayar',
        ];
    }
}
