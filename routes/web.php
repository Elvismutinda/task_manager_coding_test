<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('tasks', TaskController::class)
    ->middleware(['auth', 'verified'])
    ->names('tasks');

Route::resource('users', UserController::class)
    ->middleware(['auth','verified'])
    ->names('users');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
