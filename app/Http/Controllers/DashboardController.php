<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class lain yang namanya sama
namespace App\Http\Controllers;

// Import model Pengaduan: jembatan PHP untuk mengakses tabel pengaduan di database
use App\Models\Pengaduan;

// Import model User: jembatan PHP untuk mengakses tabel users di database
use App\Models\User;

// Class DashboardController mewarisi class Controller bawaan Laravel
// Semua method di dalam class ini = "otak" yang menangani data statistik untuk dashboard
class DashboardController extends Controller
{
    // ============ METHOD 1: DASHBOARD ADMIN ============
    // Dipanggil saat admin login dan buka URL GET /admin/dashboard
    // Tugas: hitung statistik global (semua user & semua pengaduan di sistem)
    public function admin()
    {
        // view() = tampilkan file tampilan di resources/views/admin/dashboard.blade.php
        // Argumen kedua = ARRAY data yang dikirim ke view (akan jadi variabel $totalUser & $totalPengaduan di blade)
        return view('admin.dashboard', [
            // User::count() = query SQL: SELECT COUNT(*) FROM users
            // Hasil: jumlah TOTAL semua user di sistem (admin + customer)
            'totalUser' => User::count(),

            // Pengaduan::count() = query SQL: SELECT COUNT(*) FROM pengaduan
            // Hasil: jumlah TOTAL semua pengaduan di sistem (milik semua customer)
            'totalPengaduan' => Pengaduan::count(),
        ]);
    }

    // ============ METHOD 2: DASHBOARD CUSTOMER ============
    // Dipanggil saat customer login dan buka URL GET /customer/dashboard
    // Tugas: hitung statistik personal (hanya pengaduan milik customer yang sedang login)
    public function customer()
    {
        return view('customer.dashboard', [
            // auth()->id() = mengambil ID user yang SEDANG LOGIN saat ini
            // Pengaduan::where('user_id', ...) = filter: hanya pengaduan yang user_id-nya = ID user login
            // count() = hitung berapa baris yang lolos filter
            // Query SQL yang dihasilkan: SELECT COUNT(*) FROM pengaduan WHERE user_id = [ID_USER_LOGIN]
            // Hasil: jumlah pengaduan MILIK SENDIRI saja (bukan milik customer lain)
            'totalSaya' => Pengaduan::where('user_id', auth()->id())->count(),
        ]);
    }
}