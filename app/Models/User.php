<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class model lain
namespace App\Models;

// Import HasFactory: memungkinkan model ini digunakan untuk membuat data dummy (seeder/factory)
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Import Authenticatable: class induk khusus untuk User yang memberikan fitur login, logout, dan session
// (Kita gunakan "as Authenticatable" agar tidak bentrok dengan nama class User kita sendiri)
use Illuminate\Foundation\Auth\User as Authenticatable;

// Import Notifiable: memberikan fitur notifikasi (misal: kirim email reset password atau email verifikasi)
use Illuminate\Notifications\Notifiable;

// Class User mewarisi (extends) class Authenticatable, BUKAN Model biasa
// Ini agar class ini otomatis punya fitur bawaan Laravel untuk sistem login (Auth)
class User extends Authenticatable
{
    // Menggunakan Trait (fitur tambahan) dari Laravel
    use HasFactory, Notifiable;

    // ============ KEAMANAN MASS ASSIGNMENT ============
    // protected $fillable = "Daftar Putih" kolom yang BOLEH diisi secara massal
    // Mencegah hacker memanipulasi form untuk mengisi kolom terlarang (misal: is_admin)
    protected $fillable = [
        'nama', 
        'email', 
        'password', 
        'role', // Menentukan apakah user ini 'admin' atau 'customer'
    ];

    // ============ KEAMANAN DATA SENSITIF ============
    // protected $hidden = kolom yang TIDAK BOLEH muncul saat model diubah jadi array atau JSON
    // Sangat penting agar password tidak bocor saat kita melakukan dd($user) atau membuat API
    protected $hidden = [
        'password', 
        'remember_token', // Token untuk fitur "Ingat Saya" (Remember Me)
    ];

    // ============ CASTING TIPE DATA (Laravel 11+) ============
    // Method casts() = memberi tahu Laravel cara mengonversi tipe data dari database ke PHP
    protected function casts(): array
    {
        return [
            // 'email_verified_at' akan otomatis dikonversi menjadi objek Carbon (tanggal/waktu)
            'email_verified_at' => 'datetime',
            
            // 'password' akan otomatis di-hash jika kita mengisinya dengan teks polos
            // (Fitur keamanan tambahan di Laravel 11 agar tidak lupa pakai Hash::make)
            'password' => 'hashed',
        ];
    }

    // ============ RELASI ELOQUENT (HAS MANY) ============
    // Method ini mendefinisikan hubungan: "Satu user bisa memiliki BANYAK pengaduan"
    // foreign key default-nya adalah 'user_id' di tabel pengaduan
    public function pengaduan() 
    { 
        // hasMany = relasi "memiliki banyak" (parent ke child)
        // Memungkinkan kita memanggil $user->pengaduan untuk mendapatkan semua daftar pengaduan milik user tersebut
        return $this->hasMany(Pengaduan::class); 
    }
}