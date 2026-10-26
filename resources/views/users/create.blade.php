{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten --}}
@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <h4 class="mb-4">Tambah User Baru</h4>
    
    {{-- UX BEST PRACTICE: Membatasi lebar form (500px) agar mata user fokus dan tidak lelah 
         membaca input yang terlalu melebar di layar monitor besar --}}
    <div class="card" style="max-width:500px;">
        <div class="card-body">
            
            {{-- 2. PENANGANAN ERROR VALIDASI --}}
            {{-- $errors->any() = Mengecek apakah ada aturan validasi yang dilanggar di Controller.
                 $errors->first() = HANYA menampilkan pesan error PERTAMA. 
                 Ini mencegah tampilan form menjadi "penuh" dengan kotak merah yang membuat user panik. --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            {{-- 3. FORM TAG (STANDAR KEAMANAN) --}}
            {{-- route('admin.users.store') = Named Route. Aman dan mudah di-maintain.
                 method="POST" = Standar HTTP untuk membuat data baru. --}}
            <form action="{{ route('admin.users.store') }}" method="POST">
                
                {{-- @csrf = WAJIB ADA! Token keamanan untuk mencegah serangan Cross-Site Request Forgery (CSRF) --}}
                @csrf

                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    {{-- old('nama') = UX Feature. Jika validasi gagal, nama yang sudah diketik TIDAK HILANG --}}
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Email</label>
                    {{-- type="email" = Browser akan otomatis memvalidasi format email (harus ada @) sebelum dikirim ke server --}}
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Password</label>
                    {{-- type="password" = Menyamarkan input menjadi titik-titik (bullet) untuk keamanan visual --}}
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="admin">Admin</option>
                        
                        {{-- UX DETAIL: Memberikan atribut 'selected' pada 'customer' sebagai nilai default.
                             Alasan: Dalam sistem pengaduan, pembuatan akun Customer jauh lebih sering 
                             dilakukan daripada pembuatan akun Admin. Ini menghemat 1 kali klik bagi admin. --}}
                        <option value="customer" selected>Customer</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    {{-- Tombol utama dengan warna mencolok (indigo) untuk Call to Action (CTA) --}}
                    <button class="btn text-white" style="background:#6366f1;">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    
                    {{-- UX PENTING: Tombol Batal menggunakan tag <a> (link), BUKAN <button>.
                         Jika menggunakan <button> di dalam <form>, browser bisa menganggapnya sebagai 
                         tombol submit dan mengirim form kosong. <a> memastikan user hanya dialihkan. --}}
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection