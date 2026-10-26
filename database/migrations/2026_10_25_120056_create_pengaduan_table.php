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
        // Membuat tabel 'pengaduan' untuk menyimpan data keluhan dari customer
        Schema::create('pengaduan', function (Blueprint $table) {
            
            // 1. Primary Key: ID unik untuk setiap pengaduan (auto-increment)
            $table->id();
            
            // 2. Foreign Key ke tabel 'users'
            // constrained() = otomatis mencari tabel 'users' berdasarkan nama 'user_id'
            // onDelete('cascade') = JIKA user dihapus dari database, semua pengaduan milik user ini 
            //                       akan otomatis ikut terhapus (menjaga kebersihan data / data integrity)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 3. Kategori Pengaduan (Tipe: STRING)
            // DESAIN KHUSUS: Disengaja menggunakan string biasa, BUKAN foreignId ke tabel kategori.
            // Alasan: Mempermudah struktur database, menghindari error relasi yang rumit, 
            // dan kategori bisa langsung di-hardcode di form (Infrastruktur, Pelayanan, dll).
            $table->string('kategori');
            
            // 4. Isi Pengaduan (Tipe: TEXT)
            // Menggunakan 'text' karena isi keluhan bisa sangat panjang (lebih dari 255 karakter)
            $table->text('pengaduan');
            
            // 5. Foto Bukti (Tipe: STRING, Nullable)
            // nullable() = kolom ini BOLEH dikosongkan. User tidak wajib mengupload foto.
            // Nantinya akan menyimpan path/nama file, misal: 'pengaduan/foto123.jpg'
            $table->string('foto')->nullable();
            
            // 6. Status Pengaduan (Tipe: ENUM)
            // ENUM membatasi isi kolom HANYA boleh salah satu dari 3 nilai ini.
            // default('menunggu') = Saat pengaduan baru dibuat, statusnya otomatis 'menunggu'
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            
            // 7. Timestamps
            // Otomatis membuat 2 kolom: 'created_at' (waktu buat) dan 'updated_at' (waktu update terakhir)
            $table->timestamps();
        });
    }

    // ============ METHOD DOWN: MENGHAPUS TABEL ============
    // Dipanggil otomatis saat menjalankan perintah: php artisan migrate:rollback atau migrate:fresh
    public function down(): void
    {
        // dropIfExists = hapus tabel jika ada, abaikan jika tidak ada (mencegah error saat rollback)
        Schema::dropIfExists('pengaduan');
    }
};