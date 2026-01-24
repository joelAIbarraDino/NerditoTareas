<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileAdminController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [ProfileAdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
Route::resource('admins', AdminController::class)->middleware(['auth', 'verified', 'role:admin']);
