{{-- 1. DOKUMENTASI FILE --}}
{{-- File: resources/views/layouts/auth.blade.php --}}
{{-- Fungsi: Master layout khusus untuk halaman Login dan Register. --}}
{{-- Desain dibuat minimalis (tanpa sidebar/header) agar user fokus pada form. --}}

<!DOCTYPE html>
<html lang="id"> {{-- lang="id" penting untuk aksesibilitas dan SEO bahasa Indonesia --}}
<head>
    <meta charset="UTF-8"> {{-- Wajib ada agar karakter khusus (seperti é, á) terbaca dengan benar --}}
    
    {{-- TAMBAHAN PENTING: Meta Viewport agar tampilan responsif dan tidak kekecilan di layar HP --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- @yield('title', '...') = Mengambil judul dari child view (login/register). 
         Jika child view lupa mengisi title, gunakan fallback 'Sistem Pengaduan' --}}
    <title>@yield('title', 'Sistem Pengaduan')</title>
    
    {{-- Menggunakan CDN Bootstrap agar file project tetap ringan dan tidak perlu download aset CSS lokal --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

{{-- bg login --}}
<body style="background-color: #e9ecee;">

    {{-- 2. CONTAINER FOKUS (UX BEST PRACTICE) --}}
    {{-- max-width: 420px = Lebar ideal untuk form login/register. 
         margin-top: 90px = Memberikan jarak dari atas agar form berada di posisi yang nyaman dilihat. 
         justify-content-center = Membuat form tengah end = kanan start = kiri --}}
    
    <div class="container d-flex justify-content-center" style="margin-top: 90px;">
        
        {{-- PENTING: style="width: 100%; max-width: 420px;" agar form tidak melebar --}}
        <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
            
            {{-- warna header form --}}
            <div class="card-header bg-dark text-white text-center fw-bold">
                Sistem Pengaduan
            </div>
            
            <div class="card-body">
                {{-- 3. TEMPLATE INHERITANCE (DRY PRINCIPLE) --}}
                {{-- @yield('content') = "Slot" atau tempat penyuntikan konten. 
                     File login.blade.php atau register.blade.php akan mengisi bagian ini. --}}
                @yield('content')   
            </div>
            
        </div>
        
    </div>

    {{-- Script Bootstrap JS (Wajib ada untuk fitur interaktif Bootstrap) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>