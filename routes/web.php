<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\TransactionExportController;
use App\Models\Bill;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// 🔐 Route khusus Admin
Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/generate-billing', [DashboardController::class, 'generateBilling'])->name('admin.generate-billing');

    // ✅ Manajemen Data User
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store'); // ✅ Tambah User
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// 👤 Route khusus User
use App\Http\Controllers\User\RiwayatController;

Route::middleware(['auth', CheckRole::class . ':user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bill/pay/{id}', [BillController::class, 'pay'])->name('bill.pay');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat'); // ✅ Riwayat Pembayaran
});


// 🔧 Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 📄 Tagihan Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/tagihan', [BillController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan/generate', [BillController::class, 'generate'])->name('tagihan.generate');
});

// 💳 Transaksi
Route::get('/transaksi', [App\Http\Controllers\TransactionController::class, 'index'])
    ->name('transaksi.index')
    ->middleware('auth');

// 🛠️ Utilitas: Update bulan NULL
Route::get('/update-bulan-null', function () {
    $updated = 0;
    foreach (Bill::whereNull('bulan')->get() as $bill) {
        $bill->bulan = $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('Y-m') : now()->format('Y-m');
        $bill->save();
        $updated++;
    }
    return "Sukses update $updated data tagihan yang bulan-nya null.";
});

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);

// 📤 Export Transaksi
Route::get('/admin/transaksi/export', [TransactionExportController::class, 'export'])->name('transactions.export');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
});
// 🛡️ Route bawaan Breeze
require __DIR__.'/auth.php';
