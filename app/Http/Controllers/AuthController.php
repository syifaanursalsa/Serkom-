<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class lain yang namanya sama
namespace App\Http\Controllers;

// Import model User: jembatan PHP untuk mengakses tabel users di database
use App\Models\User;

// Import class Request: pembungkus semua input yang dikirim dari form (email, password, nama, dll)
use Illuminate\Http\Request;

// Import fasade Auth: alat bawaan Laravel untuk login, logout, dan cek siapa yang sedang login
use Illuminate\Support\Facades\Auth;

// Import fasade Hash: alat untuk mengacak (hash) password agar tidak tersimpan polos di database
use Illuminate\Support\Facades\Hash;

// Class AuthController mewarisi class Controller bawaan Laravel
// Semua method di dalam class ini = "otak" yang menangani alur login, register, dan logout
class AuthController extends Controller
{
    // ============ METHOD 1: TAMPILKAN HALAMAN LOGIN ============
    // Dipanggil saat user buka URL GET /login
    public function showLogin()
    {
        // view() = tampilkan file tampilan di resources/views/auth/login.blade.php
        return view('auth.login');
    }

    // ============ METHOD 2: PROSES FORM LOGIN ============
    // Dipanggil saat user klik tombol "Masuk" di form login (POST /login)
    // Parameter $request = objek berisi semua data yang diketik user di form (email & password)
    public function login(Request $request)
    {
        // validate() = cek aturan input SEBELUM diproses
        // Jika user tidak isi / isi tidak sesuai aturan: otomatis balik ke form dengan pesan error
        $request->validate([
            'email' => 'required|email',      // email: wajib diisi + format email valid (ada @)
            'password' => 'required',         // password: wajib diisi (minimal ada isinya)
        ]);

        // Auth::attempt() = cara Laravel mencocokkan email & password dengan database
        // only('email','password') = ambil HANYA input email & password dari form (abaikan input lain)
        // Jika COCOK: session login dibuat otomatis, return TRUE
        // Jika TIDAK COCOK: return FALSE
        if (Auth::attempt($request->only('email', 'password'))) {
            // regenerate() = buat ulang ID session (mencegah serangan "session fixation")
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role user yang baru login
            // Auth::user() = objek user yang baru saja berhasil login
            // ->role = nilai role-nya ('admin' atau 'customer')
            // Hasil: route('admin.dashboard') kalau admin, route('customer.dashboard') kalau customer
            return redirect()->route(Auth::user()->role . '.dashboard');
        }

        // Jika sampai sini = login GAGAL (email/password salah)
        // back() = kembali ke halaman sebelumnya (form login)
        // withErrors() = kirim pesan error yang akan ditampilkan di view
        // onlyInput('email') = isi ulang kolom email saja (password kosong untuk keamanan)
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // ============ METHOD 3: TAMPILKAN HALAMAN REGISTER ============
    // Dipanggil saat user buka URL GET /register
    public function showRegister()
    {
        return view('auth.register');
    }

    // ============ METHOD 4: PROSES PENDAFTARAN AKUN BARU ============
    // Dipanggil saat user klik tombol "Daftar" di form register (POST /register)
    public function register(Request $request)
    {
        // Validasi input pendaftaran
        $request->validate([
            'nama' => 'required|string|max:100',            // wajib, teks, maksimal 100 karakter
            'email' => 'required|email|unique:users,email', // wajib, format email, BELUM terdaftar di tabel users
            'password' => 'required|min:6|confirmed',       // wajib, minimal 6 karakter, ada konfirmasi password
        ]);

        // User::create = INSERT baris baru ke tabel users
        // Laravel otomatis hash password karena di Model User ada 'password' => 'hashed' di $casts
        User::create([
            'nama' => $request->nama,          // ambil input nama dari form
            'email' => $request->email,        // ambil input email dari form
            'password' => Hash::make($request->password), // hash password dulu (AMAN, tidak polos)
            'role' => 'customer',              // DIPAKSA customer: pendaftaran umum tidak boleh admin
                                                // (admin hanya bisa dibuat lewat Kelola User oleh admin lain)
        ]);

        // redirect() = lempar ke halaman login
        // with('success', ...) = kirim pesan sukses sekali tampil (flash message)
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ============ METHOD 5: PROSES LOGOUT ============
    // Dipanggil saat user klik tombol "Logout" (WAJIB POST, bukan GET, demi keamanan)
    public function logout(Request $request)
    {
        // Auth::logout() = hapus status login user saat ini
        Auth::logout();

        // invalidate() = matikan semua data session (keranjang, flash message, dll)
        $request->session()->invalidate();

        // regenerateToken() = buat token CSRF baru (cegah serangan CSRF setelah logout)
        $request->session()->regenerateToken();

        // Lempar kembali ke halaman login
        return redirect()->route('login');
    }
}