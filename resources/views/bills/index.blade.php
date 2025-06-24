@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Tagihan Pengguna</h1>

        {{-- Alert pesan sukses atau warning --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        {{-- Form generate tagihan --}}
        <form action="{{ route('tagihan.generate') }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="bulan">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahun">Pilih Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Generate Tagihan Bulanan</button>
                </div>
            </div>
        </form>

        {{-- Filter tagihan --}}
        <form method="GET" action="{{ route('tagihan.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="filter_bulan">Filter Bulan</label>
                    <select name="bulan" id="filter_bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            @php
                                $value = str_pad($m, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <option value="{{ $value }}" {{ request('bulan') == $value ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filter_tahun">Filter Tahun</label>
                    <select name="tahun" id="filter_tahun" class="form-control">
                        <option value="">Semua Tahun</option>
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filter_status">Filter Status</label>
                    <select name="status" id="filter_status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>

        {{-- Tabel daftar tagihan --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Nama User</th>
                        <th>Bulan</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bills as $bill)
                        @php
                            $bulanFormatted = $bill->bulan
                                ? \Carbon\Carbon::createFromFormat('Y-m', $bill->bulan)->translatedFormat('F Y')
                                : '-';

                            $status = strtolower($bill->status);
                            $badgeClass = match ($status) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'secondary'
                            };
                            $statusLabel = match ($status) {
                                'paid' => 'Lunas',
                                'pending' => 'Belum Lunas',
                                'failed' => 'Gagal',
                                default => ucfirst($status)
                            };
                        @endphp
                        <tr>
                            <td>{{ $bill->user->name ?? '-' }}</td>
                            <td>{{ $bulanFormatted }}</td>
                            <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($bill->due_date)->format('d-m-Y') }}</td>
                            <td><span class="badge badge-{{ $badgeClass }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada tagihan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $bills->links('vendor.pagination.bootstrap-4') }}
        </div>

    </div>
@endsection
