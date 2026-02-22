<?php

use App\Http\Controllers\Auth\AfterLoginController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\MercadoPagoWebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('after-login', [AfterLoginController::class, 'handle'])->middleware(['auth'])->name('login.after');
Route::post('mercado-pago/webhook', [MercadoPagoController::class, 'webhook'])->name('mercado-pago.webhook');
Route::post('mercado-pago/{homework}/payment-gateway', [MercadoPagoController::class, 'payment'])->name('mercado-pago.payment');

require __DIR__.'/admin.php';
require __DIR__.'/specialist.php';
require __DIR__.'/client.php';
require __DIR__.'/settings.php';
