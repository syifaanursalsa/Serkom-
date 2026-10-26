{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten --}}
@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
    <h4 class="mb-4">Detail Pengaduan</h4>
    
    {{-- UX BEST PRACTICE: Membatasi lebar maksimal kartu (700px). 
         Jika terlalu lebar di monitor besar, teks akan sulit dibaca karena mata harus bergerak terlalu jauh dari kiri ke kanan. --}}
    <div class="card" style="max-width:700px;">
        <div class="card-body">
            
            {{-- 2. BARIS INFORMASI UTAMA (META DATA) --}}
            <div class="d-flex gap-2 mb-3">
                {{-- Carbon Formatting: Mengubah format tanggal database menjadi lebih mudah dibaca manusia (misal: "26 Okt 2026") --}}
                <span class="badge text-bg-secondary">{{ $pengaduan->created_at->format('d M Y') }}</span>
                
                {{-- Menampilkan kategori yang tersimpan sebagai string --}}
                <span class="badge" style="background:#6366f1;">{{ $pengaduan->kategori }}</span>
                
                {{-- Conditional Rendering: Memberikan warna badge yang berbeda sesuai status untuk visualisasi yang cepat --}}
                @if ($pengaduan->status == 'menunggu')
                    <span class="badge text-bg-warning">Menunggu</span>
                @elseif ($pengaduan->status == 'diproses')
                    <span class="badge text-bg-primary">Diproses</span>
                @else
                    <span class="badge text-bg-success">Selesai</span>
                @endif
            </div>

            <h6 class="text-muted">Isi Pengaduan</h6>
            
            {{-- FITUR UX SANGAT PENTING: white-space: pre-line; 
                 Ini memastikan bahwa setiap kali user menekan "Enter" (baris baru) saat mengetik di textarea, 
                 tampilan di halaman detail akan tetap mempertahankan baris baru tersebut. 
                 Tanpa ini, semua teks akan menyatu menjadi satu paragraf panjang yang sulit dibaca. --}}
            <p style="white-space: pre-line;">{{ $pengaduan->pengaduan }}</p>

            <h6 class="text-muted">Foto Bukti</h6>
            @if ($pengaduan->foto)
                {{-- CARA BENAR MEMANGGIL FILE UPLOAD DI LARAVEL:
                     1. asset('storage/') mengarah ke folder public/storage (yang sudah di-link via artisan storage:link)
                     2. max-width: 100% = RESPONSIVE DESIGN. Mencegah gambar besar "meledak" keluar dari kartu di layar HP.
                     3. border-radius = Estetika modern agar sudut gambar tidak tajam. --}}
                <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto Bukti"
                     style="max-width:100%; border-radius:.6rem;">
            @else
                <p class="text-muted small">Tidak ada foto bukti.</p>
            @endif

            {{-- Tombol kembali menggunakan Named Route untuk maintainability kode --}}
            <a href="{{ route('customer.pengaduan.index') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
@endsection