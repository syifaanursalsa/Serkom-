{{-- 1. TEMPLATE INHERITANCE --}}
{{-- @extends = Mewarisi layout utama (layouts.app). 
     Ini memastikan header, sidebar, dan footer tetap konsisten dengan halaman lain, 
     sehingga user tidak merasa "tersesat" saat berpindah halaman. --}}

@extends('layouts.app')

{{-- Mengisi bagian <title> di layout utama agar judul tab browser menjadi "Dashboard Customer" --}}
    
@section('title', 'Dashboard Customer')

{{-- Memulai blok konten utama yang akan disuntikkan ke @yield('content') di layouts.app --}}
@section('content')

    
    {{-- BAGIAN 1: SAMBUTAN PERSONAL (PERSONALIZATION) --}}
    
    
    {{-- auth()->user() = Helper Laravel untuk mengambil data objek user yang sedang login.
         ->nama = Memanggil kolom 'nama' secara spesifik (sesuai desain database kita).
         Ini memberikan pengalaman pengguna (UX) yang lebih ramah dan personal. --}}
    <h4 class="mb-1 fw-semibold">Halo, {{ auth()->user()->nama }} 👋</h4>
    
    {{-- text-muted = Warna teks abu-abu lembut agar tidak terlalu mencolok. mb-4 = Margin bawah untuk memberi jarak. --}}
    <p class="text-muted mb-4">Selamat datang di dashboard pengaduan.</p>

  
    {{-- BAGIAN 2: KARTU STATISTIK & CALL TO ACTION (CTA) --}}
  
    
    {{-- card p-4 = Komponen kartu Bootstrap dengan padding level 4 (ruang dalam yang lega).
         style="max-width:450px;" = UX Best Practice: Membatasi lebar maksimal agar tampilan 
         tetap rapi, fokus, dan tidak "melar" aneh di layar monitor yang sangat lebar. --}}
    <div class="card p-4" style="max-width:450px;">
        
        {{-- d-flex = Mengaktifkan Flexbox. align-items-center = menengahkan vertikal. gap-3 = jarak antar elemen. --}}
        <div class="d-flex align-items-center gap-3">
            
            {{-- Ikon dengan background bulat (inline style untuk warna spesifik yang konsisten dengan tema) --}}
            <span class="icon-circle" style="background:#eef2ff;color:#4f46e5;">
                <i class="bi bi-megaphone-fill"></i> {{-- Ikon pengeras suara dari Bootstrap Icons --}}
            </span>
            
            <div>
                <small class="text-muted d-block">Pengaduan Saya</small>
                
                {{-- {{ $totalSaya }} = Mencetak variabel yang dikirim dari Controller.
                     Tanda {{ }} otomatis melakukan ESCAPING untuk mencegah serangan XSS (Cross-Site Scripting). --}}
                <h4 class="mb-0">{{ $totalSaya }}</h4> {{-- mb-0 agar angka rapat dengan teks "Pengaduan Saya" --}}
            </div>
        </div>

        
        {{-- BAGIAN 3: TOMBOL AKSI UTAMA (CALL TO ACTION) --}}
        
        
        {{-- route('customer.pengaduan.create') = Menggunakan Named Route (Best Practice).
             Jika URL berubah di masa depan, kita hanya perlu ubah di web.php, tidak perlu ubah di view ini.
             btn-sm = Ukuran tombol kecil. text-white = Teks putih. mt-3 = Margin atas untuk jarak dari statistik.
             style="background:#6366f1;" = Warna ungu/indigo yang menarik perhatian (eye-catching) untuk aksi utama. --}}
        <a href="{{ route('customer.pengaduan.create') }}" class="btn btn-sm text-white mt-3"
           style="background:#6366f1;">
           <i class="bi bi-plus-lg"></i> Buat Pengaduan Baru
        </a>
    </div>

{{-- Menutup blok konten --}}
@endsection