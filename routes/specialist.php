<?php

use App\Http\Controllers\ProfileSpecialistController;
use Illuminate\Support\Facades\Route;

Route::get('specialist', [ProfileSpecialistController::class, 'index'])->middleware(['auth', 'verified', 'role:specialist'])->name('specialist.index');