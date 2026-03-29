<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Serve storage files via PHP (fix 404 di Railway/env tanpa symlink)
Route::get('/storage-file/{folder}/{filename}', [FileController::class, 'serve'])
    ->where('filename', '.*')
    ->name('storage.file');

Route::get('/', [FrontController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');
    Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('order.success');
    Route::post('/reward/claim', [FrontController::class, 'claimReward'])->name('reward.claim');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Login Routes
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AdminAuthController::class, 'store']);
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('services', ServiceController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
