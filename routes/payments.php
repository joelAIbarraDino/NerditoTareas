<?php

use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;

Route::post('mercado-pago/{homework}/payment-link', [MercadoPagoController::class, 'generatePaymentLink'])->middleware(['auth', 'verified', 'role:admin'])->name('mercado-pago.generate-link');
Route::post('mercado-pago/{homework}/process-payment', [MercadoPagoController::class, 'processPayment'])->name('mercado-pago.process-payment');
Route::post('mercado-pago/webhook', [MercadoPagoController::class, 'webhook'])->name('mercado-pago.webhook');
