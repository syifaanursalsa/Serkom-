{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten --}}
@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')
    <h4 class="mb-4">Form Pengaduan Baru</h4>
    
    {{-- max-width:600px = UX Best Practice: Membatasi lebar form agar mata user tidak lelah 
         membaca input yang terlalu melebar di layar monitor besar --}}
    <div class="card" style="max-width:600px;">
        <div class="card-body">
            
            {{-- 2. PENANGANAN ERROR VALIDASI --}}
            {{-- $errors->any() = Mengecek apakah ada aturan validasi yang dilanggar di Controller.
                 $errors->first() = Hanya menampilkan pesan error PERTAMA agar tampilan tidak penuh/berantakan. --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            {{-- 3. FORM TAG (SANGAT PENTING) --}}
            {{-- route('customer.pengaduan.store') = Named Route untuk keamanan dan maintainability.
                 method="POST" = Standar HTTP untuk mengirim/menyimpan data baru.
                 enctype="multipart/form-data" = WAJIB ADA! Tanpa ini, file foto TIDAK AKAN TERKIRIM ke server, 
                 yang terkirim hanya nama filenya saja (string). --}}
            <form action="{{ route('customer.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                
                {{-- @csrf = WAJIB! Token keamanan untuk mencegah serangan Cross-Site Request Forgery (CSRF) --}}
                @csrf

                <div class="mb-2">
                    <label class="form-label">Kategori</label>
                    {{-- DESAIN ARSITEKTUR: $kategoris dikirim sebagai ARRAY STRING dari Controller, 
                         BUKAN query database. Ini membuat aplikasi lebih ringan dan menghindari error relasi tabel. --}}
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        
                        @foreach ($kategoris as $k)
                            {{-- @selected = Fitur Blade modern. Otomatis menambah atribut 'selected' jika kondisi true.
                                 old('kategori') = UX Feature: Jika user salah isi form lain, pilihan kategori ini TIDAK HILANG. --}}
                            <option value="{{ $k }}" @selected(old('kategori') == $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Isi Pengaduan</label>
                    {{-- rows="4" = Memberikan tinggi awal textarea agar user tahu ini untuk teks panjang.
                         old('pengaduan') = Menyimpan teks yang sudah diketik user jika terjadi error validasi. --}}
                    <textarea name="pengaduan" class="form-control" rows="4" required>{{ old('pengaduan') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Bukti (opsional, jpg/png maks 2MB)</label>
                    {{-- TAMBAHAN UX: accept="image/*" memaksa HP/browser user HANYA menampilkan file gambar, 
                         sehingga user tidak salah pilih file PDF atau dokumen. --}}
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    {{-- Tombol utama dengan warna mencolok (indigo) untuk Call to Action (CTA) --}}
                    <button class="btn text-white" style="background:#6366f1;">
                        <i class="bi bi-send me-1"></i> Kirim Pengaduan
                    </button>
                    
                    {{-- Tombol Batal menggunakan <a> (link), BUKAN <button type="reset">. 
                         Link lebih aman karena langsung mengembalikan user ke halaman daftar tanpa risiko submit form kosong. --}}
                    <a href="{{ route('customer.pengaduan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection