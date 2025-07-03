@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">
            {{ auth()->check() && auth()->user()->role === 'admin' ? 'Laporan Transaksi' : 'Riwayat Pembayaran' }}
        </h1>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('transactions.export') }}" class="btn btn-success mb-3">
                <i class="fas fa-file-excel"></i> Export ke Excel
            </a>
        @endif

        <!-- ✅ Tambahan wrapper agar tabel bisa discroll -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Bulan Tagihan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                {{ optional($transaction->user)->name ?? (auth()->check() && auth()->user()->role === 'user' ? auth()->user()->name : '-') }}
                            </td>
                            <td>
                                @if(optional($transaction->bill)->bulan)
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $transaction->bill->bulan)->translatedFormat('F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($transaction->status) }}</td>
                            <td>
                                {{ $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- End table-responsive -->

        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
