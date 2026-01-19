<?php

use App\Http\Controllers\ProfileClientController;
use Illuminate\Support\Facades\Route;

Route::get('client', [ProfileClientController::class, 'index'])->middleware(['auth', 'verified', 'role:client'])->name('client.index');