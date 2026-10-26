{{-- 1. TEMPLATE INHERITANCE --}}
{{-- @extends = Mewarisi layout utama (master template). 
     Ini agar header, sidebar, dan footer konsisten di semua halaman tanpa perlu menulis ulang kodenya. --}}

@extends('layouts.app')

{{-- 2. INJEKSI KONTEN KE LAYOUT --}}
{{-- Mengisi bagian <title> di layout utama agar judul tab browser berubah sesuai halaman --}}

@section('title', 'Dashboard Admin')

{{-- Memulai blok konten utama. Isinya akan disuntikkan ke @yield('content') di file layouts.app --}}

@section('content')

    {{-- BAGIAN 1: HEADER / SAMBUTAN --}}
    
    {{-- auth()->user() = Helper Laravel untuk mengambil data user yang sedang login.
         ->nama = Memanggil kolom 'nama' dari tabel users. --}}

    <h4 class="mb-1 fw-semibold">Halo, {{ auth()->user()->nama }} 👋</h4>
    
    {{-- text-muted = warna teks abu-abu lembut, mb-4 = margin-bottom level 4 (jarak ke bawah) --}}

    <p class="text-muted mb-4">Ringkasan data sistem pengaduan.</p>

    {{-- BAGIAN 2: KARTU STATISTIK (BOOTSTRAP GRID) --}}
    
    {{-- row = Pembungkus kolom (Grid System). 
         g-3 = Gap (jarak) antar kolom sebesar 1rem agar tidak terlalu rapat. --}}
    <div class="row g-3">
        
        {{-- KARTU 1: TOTAL USER --}}
        {{-- col-md-4 = Di layar medium (laptop/tablet) ke atas, lebar kolom ini 4 dari 12 grid (sepertiga layar).
             Di layar HP (mobile), otomatis akan menjadi lebar penuh (12 grid) agar responsif. --}}

        <div class="col-md-4">
            {{-- card = Komponen kartu Bootstrap. p-3 = Padding level 3 (jarak dalam kartu). --}}

            <div class="card p-3">
                {{-- d-flex = Mengaktifkan Flexbox. align-items-center = menengahkan vertikal. gap-3 = jarak antar elemen. --}}

                <div class="d-flex align-items-center gap-3">
                    
                    {{-- Ikon dengan background bulat (menggunakan inline style untuk warna spesifik agar rapi) --}}

                    <span class="icon-circle" style="background:#eef2ff;color:#4f46e5;">
                        <i class="bi bi-people-fill"></i> {{-- Ikon 'orang' dari Bootstrap Icons --}}
                    </span>
                    
                    <div>
                        {{-- d-block = Memaksa elemen <small> turun ke baris baru (block level) --}}

                        <small class="text-muted d-block">Total User</small>
                        
                        {{-- {{ $totalUser }} = Mencetak variabel yang dikirim dari Controller (DashboardController).
                             PENTING: Tanda kurung kurawal ganda {{ }} otomatis melakukan ESCAPING. 
                             Ini mencegah serangan XSS (Cross-Site Scripting) jika data mengandung kode HTML berbahaya. --}}
                             
                        <h4 class="mb-0">{{ $totalUser }}</h4> {{-- mb-0 = margin-bottom: 0, agar angka rapat dengan teks "Total User" --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU 2: TOTAL PENGADUAN --}}
        
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="icon-circle" style="background:#fff7ed;color:#ea580c;">
                        <i class="bi bi-megaphone-fill"></i> {{-- Ikon 'pengeras suara' untuk pengaduan --}}
                    </span>
                    <div>
                        <small class="text-muted d-block">Total Pengaduan</small>
                        <h4 class="mb-0">{{ $totalPengaduan }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

{{-- Menutup blok konten --}}
@endsection