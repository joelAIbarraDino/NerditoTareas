<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\SpecialistAreaController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TypeHomeworkController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [ProfileAdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
Route::resource('admins', AdminController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('specialists', SpecialistController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('specialist-areas', SpecialistAreaController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('clients', ClientController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('homework', HomeworkController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('type-homework', TypeHomeworkController::class)->middleware(['auth', 'verified', 'role:admin']);


Route::get('mercado-pago/{homework}', [MercadoPagoController::class, 'show'])->middleware(['auth', 'verified', 'role:admin' ])->name('mercado-pago.show');
Route::post('mercado-pago/{homework}/payment-link', [MercadoPagoController::class, 'generatePaymentLink'])->middleware(['auth', 'verified', 'role:admin'])->name('mercado-pago.generatePaymentLink');