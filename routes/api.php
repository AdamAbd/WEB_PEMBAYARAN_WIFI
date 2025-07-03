<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are stateless and automatically skip CSRF.
|
*/

Route::post('midtrans/callback', [MidtransCallbackController::class, 'handleCallback'])
     ->name('midtrans.callback');
