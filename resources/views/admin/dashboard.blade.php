@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard Admin</h1>

        <div class="row">

            <!-- Total User -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card text-white bg-primary shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Total User</h5>
                        <h3>{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Pendapatan -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card text-white bg-success shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Pendapatan Bulan Ini</h5>
                        <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- User Sudah Bayar -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card text-white bg-info shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle"></i> User Sudah Bayar</h5>
                        <h3>{{ $userSudahBayar }}</h3>
                    </div>
                </div>
            </div>

            <!-- User Belum Bayar -->
            <div class="col-12 col-md-3 mb-4">
                <div class="card text-white bg-warning shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-times-circle"></i> User Belum Bayar</h5>
                        <h3>{{ $userBelumBayar }}</h3>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection