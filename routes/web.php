<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. REDIRECT ROOT URL
// Jika user membuka domain utama (misal: localhost:8000/), otomatis diarahkan ke halaman login
Route::redirect('/', '/login');

// =================================================================
// 2. AREA TAMU (GUEST) - HANYA UNTUK YANG BELUM LOGIN
// =================================================================
// Middleware 'guest' memastikan jika user SUDAH login, mereka tidak bisa 
// membuka halaman login/register lagi (akan otomatis di-redirect ke dashboard)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
});

// =================================================================
// 3. AREA WAJIB LOGIN (AUTHENTICATED)
// =================================================================
// Middleware 'auth' adalah gerbang utama. Semua route di dalam grup ini 
// TIDAK BISA diakses jika user belum login.
Route::middleware('auth')->group(function () {
    
    // KEAMANAN TINGGI: Logout WAJIB menggunakan method POST, BUKAN GET.
    // Ini mencegah serangan CSRF dan mencegah user ter-logout secara tidak sengaja oleh web crawler.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // SMART REDIRECT: Rute perantara yang dinamis.
    // Otomatis membaca role user yang login, lalu mengarahkan ke dashboard yang sesuai.
    // Contoh: Jika role='admin', akan diarahkan ke route 'admin.dashboard'.
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    // =================================================================
    // 4. AREA KHUSUS ADMIN
    // =================================================================
    // Route Grouping: Mengelompokkan route agar kode lebih rapi (DRY Principle).
    // - middleware('role:admin'): Hanya user dengan role 'admin' yang bisa akses.
    // - prefix('admin'): Menambahkan '/admin' di awal semua URL di dalam grup ini.
    // - name('admin.'): Menambahkan prefix 'admin.' pada semua named route di dalam grup.
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Sub-Group untuk Manajemen User (CRUD)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');           // GET  /admin/users
            Route::get('/create', [UserController::class, 'create'])->name('create');   // GET  /admin/users/create
            Route::post('/', [UserController::class, 'store'])->name('store');          // POST /admin/users
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');    // GET  /admin/users/{id}/edit
            Route::put('/{id}', [UserController::class, 'update'])->name('update');     // PUT  /admin/users/{id}
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');// DELETE /admin/users/{id}
        });

        // Sub-Group untuk Kelola Pengaduan
        Route::prefix('kelola-pengaduan')->name('kelola.')->group(function () {
            Route::get('/', [PengaduanController::class, 'kelola'])->name('pengaduan');
            Route::put('/{id}/status', [PengaduanController::class, 'updateStatus'])->name('status');
        });
    });

    // =================================================================
    // 5. AREA KHUSUS CUSTOMER
    // =================================================================
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');

        // Sub-Group untuk Pengaduan Milik Sendiri
        Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
            Route::get('/', [PengaduanController::class, 'saya'])->name('index');
            Route::get('/create', [PengaduanController::class, 'create'])->name('create');
            Route::post('/', [PengaduanController::class, 'store'])->name('store');
            Route::get('/{id}', [PengaduanController::class, 'detail'])->name('detail');
        });
    });
});