<?php

use App\Http\Controllers\Auth\AfterLoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('after-login', [AfterLoginController::class, 'handle'])->middleware(['auth'])->name('login.after');

require __DIR__.'/admin.php';
require __DIR__.'/specialist.php';
require __DIR__.'/client.php';
require __DIR__.'/settings.php';
