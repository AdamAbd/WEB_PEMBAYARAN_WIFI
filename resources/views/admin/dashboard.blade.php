@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container">
        <h1 class="mb-4 fw-bold text-gray-800">Dashboard Admin</h1>

        <div class="row">
            <!-- Total User -->
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="font-weight-bold">👥 Total User</h6>
                                <h3 class="mb-0">{{ $totalUsers }}</h3>
                            </div>
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan Bulan Ini -->
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card bg-success text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="font-weight-bold">💰 Pendapatan Bulan Ini</h6>
                                <h3 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                            </div>
                            <i class="fas fa-wallet fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Sudah Bayar -->
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card bg-info text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="font-weight-bold">✅ User Sudah Bayar</h6>
                                <h3 class="mb-0">{{ $userSudahBayar }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Belum Bayar -->
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card bg-warning text-dark shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="font-weight-bold">❌ User Belum Bayar</h6>
                                <h3 class="mb-0">{{ $userBelumBayar }}</h3>
                            </div>
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
