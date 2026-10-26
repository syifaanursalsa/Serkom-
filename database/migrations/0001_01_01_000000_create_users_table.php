<?php

// Import class Migration: class induk untuk semua file migrasi database di Laravel
use Illuminate\Database\Migrations\Migration;

// Import class Blueprint: "cetak biru" atau alat untuk mendesain struktur kolom dalam tabel
use Illuminate\Database\Schema\Blueprint;

// Import fasade Schema: alat utama untuk membuat, mengubah, atau menghapus tabel di database
use Illuminate\Support\Facades\Schema;

// Class anonim yang mewarisi class Migration
// Laravel akan menjalankan method up() saat migrate, dan down() saat rollback/fresh
return new class extends Migration
{
    // ============ METHOD UP: MEMBUAT TABEL ============
    // Dipanggil otomatis saat menjalankan perintah: php artisan migrate
    public function up(): void
    {
        // 1. MEMBUAT TABEL 'users' (Penyimpanan data pengguna)
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Kolom 'id' sebagai Primary Key (auto-increment bigint)
            
            $table->string('nama'); // Kolom nama, tipe data VARCHAR(255)
            
            // Kolom email dengan constraint UNIQUE (tidak boleh ada email yang sama/duplikat)
            $table->string('email')->unique(); 
            
            // Kolom waktu verifikasi email. nullable() berarti boleh kosong (null)
            $table->timestamp('email_verified_at')->nullable(); 
            
            $table->string('password'); // Kolom untuk menyimpan password yang sudah di-hash
            
            // Kolom role dengan tipe ENUM. 
            // HANYA boleh berisi 'admin' atau 'customer'. Default-nya adalah 'customer'
            // Ini lebih aman daripada string biasa karena membatasi input yang tidak valid
            $table->enum('role', ['admin', 'customer'])->default('customer'); 
            
            $table->rememberToken(); // Kolom 'remember_token' untuk fitur "Ingat Saya" (Remember Me) saat login
            
            $table->timestamps(); // Otomatis membuat 2 kolom: 'created_at' dan 'updated_at'
        });

        // 2. MEMBUAT TABEL 'password_reset_tokens' (Bawaan Laravel)
        // Digunakan oleh Laravel untuk menyimpan token sementara saat user lupa password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email sebagai primary key di tabel ini
            $table->string('token'); // Token acak yang dikirim ke email user
            $table->timestamp('created_at')->nullable(); // Waktu token dibuat
        });

        // 3. MEMBUAT TABEL 'sessions' (Bawaan Laravel)
        // Digunakan untuk menyimpan data sesi login user di database (lebih aman daripada file)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID sesi yang unik
            $table->foreignId('user_id')->nullable()->index(); // ID user yang sedang login (nullable jika guest)
            $table->string('ip_address', 45)->nullable(); // Menyimpan IP address user (untuk keamanan)
            $table->text('user_agent')->nullable(); // Menyimpan info browser/device user
            $table->longText('payload'); // Data sesi yang dienkripsi
            $table->integer('last_activity')->index(); // Waktu terakhir user beraktivitas (untuk auto-logout)
        });
    }

    // ============ METHOD DOWN: MENGHAPUS TABEL ============
    // Dipanggil otomatis saat menjalankan perintah: php artisan migrate:rollback atau migrate:fresh
    // PENTING: Urutan penghapusan harus terbalik atau menggunakan dropIfExists agar tidak error
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};