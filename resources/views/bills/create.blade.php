<!-- resources/views/bills/create.blade.php -->
@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Tambah Tagihan Bulanan</h1>

<form action="{{ route('add-bill-all') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="amount">Jumlah Tagihan</label>
        <input type="number" class="form-control" id="amount" name="amount" required>
    </div>
    <div class="form-group">
        <label for="due_date">Tanggal Jatuh Tempo</label>
        <input type="date" class="form-control" id="due_date" name="due_date" required>
    </div>
    <button type="submit" class="btn btn-primary">Tambah Tagihan</button>
</form>
@endsection
