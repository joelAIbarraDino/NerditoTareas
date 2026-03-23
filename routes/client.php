<?php

use App\Http\Controllers\ProfileClientController;
use Illuminate\Support\Facades\Route;

Route::get('client', [ProfileClientController::class, 'index'])->middleware(['auth', 'verified', 'role:client'])->name('client.index');
Route::get('client/{order_id}/payment', [ProfileClientController::class, 'homeworkPayment'])->middleware(['auth', 'verified', 'role:client'])->name('client.checkout-homewrok');
Route::get('client/create-homework', [ProfileClientController::class, 'createHomework'])->middleware(['auth', 'verified', 'role:client'])->name('client.create-homework');
Route::post('client/save-homework', [ProfileClientController::class, 'saveHomework'])->middleware(['auth', 'verified', 'role:client'])->name('client.save-homework');