<?php
use App\Http\Controllers\ProfileAdminController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [ProfileAdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
