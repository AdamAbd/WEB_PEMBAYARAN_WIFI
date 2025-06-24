@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Selamat Datang, {{ \Illuminate\Support\Facades\Auth::user()->name }}</h1>

    {{-- 🔔 Tagihan Belum Dibayar --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Tagihan Belum Dibayar</h6>
        </div>
        <div class="card-body">
            @if($unpaidBills->isEmpty())
                <p class="text-muted">Tidak ada tagihan yang belum dibayar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Jumlah</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidBills as $bill)
                                <tr>
                                    <td>
                                        {{ $bill->bulan 
                                            ? \Carbon\Carbon::createFromFormat('Y-m', $bill->bulan)->translatedFormat('F Y') 
                                            : '-' }}
                                    </td>
                                    <td>Rp{{ number_format($bill->amount, 0, ',', '.') }}</td>
                                    <td>
                                        {{ $bill->due_date 
                                            ? \Carbon\Carbon::parse($bill->due_date)->format('d-m-Y') 
                                            : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('user.bill.pay', $bill->id) }}" class="btn btn-sm btn-primary">
                                            Bayar Sekarang
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
