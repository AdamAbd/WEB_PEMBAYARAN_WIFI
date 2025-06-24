@extends('layouts.app')

@section('title', 'Pembayaran Tagihan')

@section('content')
<div class="container mt-4">
    <h4>Tagihan Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $bill->bulan)->translatedFormat('F Y') }}</h4>
    <p>Total Pembayaran: <strong>Rp {{ number_format($bill->amount, 0, ',', '.') }}</strong></p>

    <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                alert("Pembayaran berhasil!");
                window.location.href = "/user/dashboard";
            },
            onPending: function(result) {
                alert("Pembayaran tertunda.");
                window.location.href = "/user/dashboard";
            },
            onError: function(result) {
                alert("Terjadi kesalahan dalam pembayaran.");
            }
        });
    };
</script>
@endsection
