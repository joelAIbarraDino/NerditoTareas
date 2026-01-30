<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\SpecialistAreaController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [ProfileAdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');
Route::resource('admins', AdminController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('specialists', SpecialistController::class)->middleware(['auth', 'verified', 'role:admin']);
Route::resource('specialist-areas', SpecialistAreaController::class)->middleware(['auth', 'verified', 'role:admin']);