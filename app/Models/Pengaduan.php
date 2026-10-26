<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class model lain yang namanya sama
namespace App\Models;

// Import class Model: "jembatan ajaib" (Eloquent ORM) bawaan Laravel 
// yang memberi kemampuan class ini untuk berinteraksi dengan tabel di database
use Illuminate\Database\Eloquent\Model;

// Class Pengaduan mewarisi (extends) class Model bawaan Laravel
// Class ini mewakili SATU BARIS data di tabel 'pengaduan'
class Pengaduan extends Model
{
    // ============ KONFIGURASI TABEL ============
    // protected $table = secara eksplisit memberi tahu Laravel nama tabel di database
    // (Laravel sebenarnya bisa menebak otomatis jadi 'pengaduans', tapi menulis manual lebih aman dan rapi)
    protected $table = 'pengaduan';

    // ============ KEAMANAN MASS ASSIGNMENT ============
    // protected $fillable = "Daftar Putih" (whitelist) kolom yang BOLEH diisi secara massal
    // Ini adalah fitur keamanan CRUCIAL untuk mencegah serangan "Mass Assignment Vulnerability"
    // Hanya kolom yang ditulis di sini yang bisa diisi lewat method create() atau update()
    protected $fillable = [
        'user_id',    // ID user yang membuat pengaduan
        'kategori',   // Kategori pengaduan (disimpan sebagai teks/string, bukan relasi tabel)
        'pengaduan',  // Isi/keluhan pengaduan
        'foto',       // Path/nama file foto bukti
        'status',     // Status pengaduan (menunggu, diproses, selesai)
    ];

    // ============ RELASI ELOQUENT (BELONGS TO) ============
    // Method ini mendefinisikan hubungan: "Setiap pengaduan dimiliki oleh SATU user"
    // foreign key default-nya adalah 'user_id' (sesuai nama kolom di tabel pengaduan)
    public function user() 
    { 
        // belongsTo = relasi "milik" (child ke parent)
        // Memungkinkan kita memanggil $pengaduan->user->nama di Controller atau Blade View
        return $this->belongsTo(User::class); 
    }
}