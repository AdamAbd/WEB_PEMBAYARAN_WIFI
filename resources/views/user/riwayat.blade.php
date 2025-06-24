@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Riwayat Transaksi</h3>

    @if($transactions->isEmpty())
        <p class="text-muted">Belum ada transaksi.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                            <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-success">Paid</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
