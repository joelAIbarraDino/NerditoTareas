<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\SpecialistAreaController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [ProfileAdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
Route::resource('admins', AdminController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('specialist-area', SpecialistAreaController::class)->middleware(['auth', 'verified', 'role:admin']);