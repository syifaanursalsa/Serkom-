<?php

// Import class Migration: class induk untuk semua file migrasi database
use Illuminate\Database\Migrations\Migration;

// Import class Blueprint: alat untuk mendesain struktur kolom dalam tabel
use Illuminate\Database\Schema\Blueprint;

// Import fasade Schema: alat utama untuk membuat, mengubah, atau menghapus tabel
use Illuminate\Support\Facades\Schema;

// Class anonim untuk migrasi tabel cache bawaan Laravel
// Digunakan saat driver cache di file .env diset ke 'database' (CACHE_STORE=database)
return new class extends Migration
{
    // ============ METHOD UP: MEMBUAT TABEL CACHE ============
    // Dipanggil otomatis saat menjalankan: php artisan migrate
    public function up(): void
    {
        // 1. MEMBUAT TABEL 'cache'
        // Fungsi: Menyimpan data yang sering diakses (seperti hasil query atau API) 
        // agar aplikasi lebih cepat dan tidak terus-menerus membebani database utama.
        Schema::create('cache', function (Blueprint $table) {
            // 'key': Identifier unik untuk data yang disimpan (Primary Key)
            $table->string('key')->primary();
            
            // 'value': Isi data yang disimpan (bisa berupa string, array, atau objek yang di-serialize)
            // mediumText digunakan karena data cache bisa berukuran cukup besar
            $table->mediumText('value');
            
            // 'expiration': Waktu kadaluarsa data cache dalam format timestamp UNIX
            // Setelah waktu ini terlewati, Laravel akan otomatis menghapus data ini
            $table->integer('expiration');
        });

        // 2. MEMBUAT TABEL 'cache_locks'
        // Fungsi: Mencegah "Race Condition" (konkurensi). 
        // Jika dua proses/server mencoba memperbarui data cache yang sama secara bersamaan, 
        // tabel ini mengunci (lock) proses tersebut agar hanya satu yang bisa mengeksekusi pada satu waktu.
        Schema::create('cache_locks', function (Blueprint $table) {
            // 'key': Identifier unik untuk kunci yang sedang dipegang (Primary Key)
            $table->string('key')->primary();
            
            // 'owner': Identifier proses atau server yang sedang memegang kunci tersebut
            $table->string('owner');
            
            // 'expiration': Waktu kadaluarsa kunci. Jika proses macet (crash), 
            // kunci akan otomatis lepas setelah waktu ini agar tidak mengunci selamanya (deadlock)
            $table->integer('expiration');
        });
    }

    // ============ METHOD DOWN: MENGHAPUS TABEL CACHE ============
    // Dipanggil otomatis saat menjalankan: php artisan migrate:rollback atau migrate:fresh
    public function down(): void
    {
        // Hapus tabel locks dulu, baru tabel cache utama
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};