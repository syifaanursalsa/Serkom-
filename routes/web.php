<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// area tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
});

// area wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('kelola-pengaduan')->name('kelola.')->group(function () {
            Route::get('/', [PengaduanController::class, 'kelola'])->name('pengaduan');
            Route::put('/{id}/status', [PengaduanController::class, 'updateStatus'])->name('status');
        });
    });

    // CUSTOMER
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');

        Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
            Route::get('/', [PengaduanController::class, 'saya'])->name('index');
            Route::get('/create', [PengaduanController::class, 'create'])->name('create');
            Route::post('/', [PengaduanController::class, 'store'])->name('store');
            Route::get('/{id}', [PengaduanController::class, 'detail'])->name('detail');
        });
    });
});