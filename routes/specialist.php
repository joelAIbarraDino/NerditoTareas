<?php

use App\Http\Controllers\ProfileSpecialistController;
use Illuminate\Support\Facades\Route;

Route::get('specialist', [ProfileSpecialistController::class, 'index'])->middleware(['auth', 'verified', 'role:specialist'])->name('specialist.index');
Route::get('specialist/homework-history', [ProfileSpecialistController::class, 'homeworkHistoy'])->middleware(['auth', 'verified', 'role:specialist'])->name('specialist.index');
Route::get('specialist/payments', [ProfileSpecialistController::class, 'payments'])->middleware(['auth', 'verified', 'role:specialist'])->name('specialist.index');