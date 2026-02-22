<?php

use App\Http\Controllers\ProfileClientController;
use Illuminate\Support\Facades\Route;

Route::get('client', [ProfileClientController::class, 'index'])->middleware(['auth', 'verified', 'role:client'])->name('client.index');

//pago de tarea
Route::get('client/{order_id}/payment', [ProfileClientController::class, 'homeworkPayment'])->middleware(['auth', 'verified', 'role:client'])->name('client.index');