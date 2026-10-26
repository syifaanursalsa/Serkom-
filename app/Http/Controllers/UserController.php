<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class lain yang namanya sama
namespace App\Http\Controllers;

// Import model User: jembatan PHP untuk mengakses tabel users di database
use App\Models\User;

// Import class Request: pembungkus semua input yang dikirim dari form
use Illuminate\Http\Request;

// Import fasade Hash: alat untuk mengacak (hash) password agar tidak tersimpan polos
use Illuminate\Support\Facades\Hash;

// Class UserController mewarisi class Controller bawaan Laravel
// Semua method di dalam class ini = "otak" yang menangani CRUD user (khusus admin)
class UserController extends Controller
{
    // ============ METHOD 1: DAFTAR SEMUA USER (READ) ============
    // Dipanggil saat admin buka URL GET /admin/users
    public function index()
    {
        // User::latest() = urutkan berdasarkan created_at DESC (user terbaru di atas)
        // get() = eksekusi query, return collection (array objek)
        // Query SQL: SELECT * FROM users ORDER BY created_at DESC
        $users = User::latest()->get();

        // compact('users') = shortcut mengirim variabel ke view
        // Sama dengan: ['users' => $users]
        return view('users.index', compact('users'));
    }

    // ============ METHOD 2: TAMPILKAN FORM TAMBAH USER (CREATE) ============
    // Dipanggil saat admin klik tombol "+ Tambah User" (GET /admin/users/create)
    public function create()
    {
        // Tampilkan form kosong untuk tambah user baru
        return view('users.create');
    }

    // ============ METHOD 3: SIMPAN USER BARU (CREATE) ============
    // Dipanggil saat admin klik "Simpan" di form tambah user (POST /admin/users)
    // Parameter $request = objek berisi semua input dari form (nama, email, password, role)
    public function store(Request $request)
    {
        // Validasi input SEBELUM diproses
        $request->validate([
            'nama' => 'required|string|max:100',            // wajib, teks, maks 100 karakter
            'email' => 'required|email|unique:users,email', // wajib, format email, BELUM terdaftar di tabel users
            'password' => 'required|min:6',                 // wajib, minimal 6 karakter
            'role' => 'required|in:admin,customer',         // wajib, harus salah satu dari: admin atau customer
        ]);

        // User::create = INSERT baris baru ke tabel users
        User::create([
            'nama' => $request->nama,          // ambil input nama dari form
            'email' => $request->email,        // ambil input email dari form
            'password' => Hash::make($request->password), // hash password (AMAN, tidak polos)
            'role' => $request->role,          // admin bebas pilih role user baru
        ]);

        // Redirect ke daftar user + flash message sukses
        // Prefix 'admin.' = karena route dibungkus dalam grup admin di web.php
        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    // ============ METHOD 4: TAMPILKAN FORM EDIT USER (UPDATE) ============
    // Dipanggil saat admin klik tombol "Edit" (GET /admin/users/{id}/edit)
    // Parameter $id = ID user yang akan diedit (dari URL)
    public function edit($id)
    {
        // findOrFail($id) = cari user by ID; kalau tidak ada -> error 404
        // Lebih aman dari find() biasa yang return null
        $user = User::findOrFail($id);

        // Kirim objek $user ke view (biar form terisi data lama)
        return view('users.edit', compact('user'));
    }

    // ============ METHOD 5: SIMPAN PERUBAHAN USER (UPDATE) ============
    // Dipanggil saat admin klik "Simpan Perubahan" di form edit (PUT /admin/users/{id})
    public function update(Request $request, $id)
    {
        // Cari user yang akan diupdate
        $user = User::findOrFail($id);

        // Validasi dengan pengecualian unik untuk email
        $request->validate([
            'nama' => 'required|string|max:100',
            // unique:users,email,' . $user->id = email harus unik KECUALI untuk baris dengan ID ini
            // Ini penting! Tanpa ini, admin tidak bisa save tanpa ubah email
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,customer',
        ]);

        // Update field per field (bukan mass assignment)
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->role = $request->role;

        // Password OPSIONAL saat edit
        // filled('password') = true HANYA jika kolom password diisi (tidak kosong)
        // Kalau admin tidak isi password: password lama TIDAK diganti
        // Kalau admin isi password baru: password di-hash dan diganti
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // save() = simpan perubahan ke database (eksekusi query UPDATE)
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    // ============ METHOD 6: HAPUS USER (DELETE) ============
    // Dipanggil saat admin klik tombol "Hapus" + konfirmasi (DELETE /admin/users/{id})
    public function destroy($id)
    {
        // Cari user yang akan dihapus
        $user = User::findOrFail($id);

        // KEAMANAN PENTING: cegah admin menghapus akun sendiri
        // auth()->id() = ID user yang sedang login
        // Kalau admin coba hapus dirinya sendiri: tolak dengan pesan error
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Hapus user dari database (query DELETE)
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}