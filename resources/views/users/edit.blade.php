{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar konsistensi UI/UX terjaga --}}
@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    {{-- Menampilkan nama user yang sedang diedit di judul agar admin tidak salah orang --}}
    <h4 class="mb-4">Edit User: {{ $user->nama }}</h4>
    
    <div class="card" style="max-width:500px;">
        <div class="card-body">
            
            {{-- 2. PENANGANAN ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            {{-- 3. FORM UPDATE (RESTFUL ARCHITECTURE) --}}
            {{-- route('admin.users.update', $user->id) = Mengirim ID user ke route untuk ditargetkan --}}
            {{-- method="POST" = HTML form standar --}}
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                
                @csrf
                
                {{-- @method('PUT') = METHOD SPOOFING. 
                     Karena HTML form hanya mendukung GET dan POST, kita gunakan ini untuk memberi tahu 
                     Laravel Router bahwa request ini adalah operasi UPDATE, sesuai standar RESTful API. --}}
                @method('PUT')

                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    {{-- old('nama', $user->nama) = FALLBACK 2 LAPIS (Sangat Pro!):
                         1. Jika validasi gagal, ambil input terakhir yang diketik user (old).
                         2. Jika halaman baru dibuka, ambil data dari database ($user->nama). --}}
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-2">
                    {{-- UX DETAIL: Memberi tahu user bahwa field ini opsional saat edit --}}
                    <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diganti)</small></label>
                    
                    {{-- TIDAK ada atribut 'required' di sini. Ini sengaja agar admin tidak dipaksa 
                         membuat password baru setiap kali hanya ingin mengedit nama atau role. --}}
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        {{-- @selected = Fitur Blade modern. Otomatis menambahkan atribut 'selected' 
                             jika kondisi di dalamnya bernilai true. Jauh lebih bersih daripada if-else. --}}
                        <option value="admin" @selected($user->role == 'admin')>Admin</option>
                        <option value="customer" @selected($user->role == 'customer')>Customer</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn text-white" style="background:#6366f1;">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    
                    {{-- Tombol Batal menggunakan <a> untuk mencegah accidental form submission --}}
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection