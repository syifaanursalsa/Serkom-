{{-- 1. BLADE COMPONENT (Modern Laravel) --}}
{{-- <x-app-layout> adalah fitur Blade Component (Laravel 7+). 
     Ini pengganti modern dari @extends('layouts.app'). 
     Memberikan cara yang lebih rapi dan terenkapsulasi untuk mewarisi layout utama. --}}
<x-app-layout>

    {{-- 2. CUSTOM CSS (RESPONSIVE & INTERACTIVE) --}}
    <style>
        /* CSS GRID: Membuat layout kartu yang otomatis responsif tanpa media query */
        .dashboard-statistics {
            display: grid;
            /* auto-fit: Otomatis menyesuaikan jumlah kolom berdasarkan lebar layar.
               minmax(min(100%, 240px), 1fr): Minimal lebar kartu 240px, maksimal 1 fraksi ruang.
               Jika layar HP, akan jadi 1 kolom. Jika laptop, bisa jadi 2-4 kolom otomatis. */
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr));
            gap: 24px; /* Jarak antar kartu */
            margin-bottom: 32px;
        }

        /* Desain Kartu Statistik */
        .dashboard-stat-card {
            min-height: 132px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 20px; /* Sudut membulat modern */
            /* Gradient halus agar terlihat premium */
            background: linear-gradient(145deg, #ffffff, #fafafa);
            box-shadow: 0 8px 24px rgb(17 24 39 / 6%);
            /* Transisi halus untuk efek hover (durasi 160ms) */
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        /* Efek Hover: Kartu naik sedikit dan bayangan membesar saat kursor diarahkan */
        .dashboard-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgb(17 24 39 / 10%);
        }

        .dashboard-stat-label {
            margin: 0;
            color: #6b7280; /* Warna abu-abu untuk teks label */
            font-size: 14px;
            font-weight: 500;
        }

        .dashboard-stat-value {
            margin: 10px 0 0;
            color: #5d96e0; /* Warna hitam pekat untuk angka agar kontras */
            font-size: 36px;
            font-weight: 700;
            line-height: 1;
        }
    </style>

    {{-- 3. HEADER DASHBOARD --}}
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-600 text-sm uppercase tracking-wide font-semibold">DASHBOARD MASYARAKAT</p>
                
                {{-- Auth::user()->name = Mengambil nama user yang sedang login.
                     (Catatan: Pastikan di database kolomnya 'name'. Jika kolommu 'nama', ubah jadi Auth::user()->nama) --}}
                <h1 class="text-3xl font-bold text-gray-900 mt-1">Halo, {{ Auth::user()->name }}</h1>
                
                <p class="text-gray-600 mt-2">Pantau laporan dan perkembangan layanan masyarakat.</p>
            </div>
            
            <div class="text-right">
                {{-- now() = Helper Laravel yang memanggil library Carbon.
                     translatedFormat('l, d F Y') = Format tanggal otomatis dalam BAHASA INDONESIA.
                     Contoh output: "Senin, 26 Oktober 2026" --}}
                <p class="text-gray-500 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- 4. KARTU STATISTIK (GRID 4 KOLOM) --}}
    <div class="dashboard-statistics">
        <div class="dashboard-stat-card">
            <p class="dashboard-stat-label">Total pengaduan</p>
            {{-- ?? 0 = Null Coalescing Operator. 
                 Jika variabel $totalPengaduan kosong/null, tampilkan angka 0. 
                 Ini mencegah error "Undefined variable" di halaman. --}}
            <p class="dashboard-stat-value">{{ $totalPengaduan ?? 0 }}</p>
        </div>

        <div class="dashboard-stat-card">
            <p class="dashboard-stat-label">Menunggu</p>
            <p class="dashboard-stat-value">{{ $menungguPengaduan ?? 0 }}</p>
        </div>

        <div class="dashboard-stat-card">
            <p class="dashboard-stat-label">Diproses</p>
            <p class="dashboard-stat-value">{{ $diprosesPengaduan ?? 0 }}</p>
        </div>

        <div class="dashboard-stat-card">
            <p class="dashboard-stat-label">Selesai</p>
            <p class="dashboard-stat-value">{{ $selesaiPengaduan ?? 0 }}</p>
        </div>
    </div>

</x-app-layout>