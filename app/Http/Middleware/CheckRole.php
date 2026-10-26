<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class middleware lain
namespace App\Http\Middleware;

// Import class Closure: tipe data untuk fungsi anonymous (callback) yang akan menjalankan request selanjutnya
use Closure;

// Import class Request: pembungkus semua data HTTP request yang masuk dari browser
use Illuminate\Http\Request;

// Class CheckRole = "Satpam" atau penjaga gerbang aplikasi
// Bertugas memastikan user yang sedang login memiliki role (hak akses) yang diizinkan untuk membuka halaman tertentu
class CheckRole
{
    // ============ METHOD: HANDLE (CEK HAK AKSES) ============
    // Dipanggil otomatis oleh Laravel setiap kali ada route yang memakai middleware ini di web.php
    // Contoh di route: middleware('role:admin') atau middleware('role:admin,customer')
    // Parameter ...$roles (variadic) = menampung argumen setelah titik dua menjadi array, misal: ['admin']
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // auth()->user() = mengambil data objek user yang sedang login saat ini
        $user = auth()->user();

        // Cek kondisi keamanan:
        // 1. !$user = user belum login (masih guest)
        // 2. !in_array($user->role, $roles) = role user tidak ada di dalam daftar $roles yang diizinkan
        if (!$user || !in_array($user->role, $roles)) {
            
            // abort(403, '...') = hentikan proses request, tampilkan halaman error 403 Forbidden (Dilarang Masuk)
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        // Jika lolos pengecekan di atas, izinkan request untuk lanjut ke tujuan aslinya (Controller atau Middleware berikutnya)
        // $next($request) = eksekusi langkah selanjutnya dalam pipeline Laravel
        return $next($request);
    }
}