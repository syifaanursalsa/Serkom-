<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- @yield('title', '...') = Mengambil judul dari child view. 
         Jika child view tidak mendefinisikan title, gunakan fallback 'Sistem Pengaduan' --}}
    <title>@yield('title', 'Sistem Pengaduan')</title>
    
    {{-- Menggunakan CDN untuk Bootstrap 5.3.3 dan Bootstrap Icons agar tidak perlu download file lokal --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        /* Layout Dasar: Background abu-abu lembut agar konten card lebih menonjol */
        body { background: #e8ecf0; }
        
        /* Sidebar Fixed: Lebar 260px, menempel di kiri layar dari atas sampai bawah */
        .sidebar { width: 260px; background: #edeaea; border-right: 1px solid #eff0f3;
                   position: fixed; top: 0; bottom: 0; left: 0;
                   display: flex; flex-direction: column; padding: 1.25rem 1rem; }
        
        /* Branding Sidebar */
        .sidebar .brand { display: flex; align-items: center; gap: .6rem;
                          padding: 0 .25rem 1rem; border-bottom: 1px solid #f2f1ee; margin-bottom: 1rem; }
        .sidebar .brand i { background: linear-gradient(135deg, #797bdc, #8b5cf6); color: #fff;
                            padding: .5rem .6rem; border-radius: .7rem; }
        .sidebar .brand span { font-weight: 700; color: #1f2937; }
        
        /* Menu Navigasi */
        .sidebar a.menu { display: flex; align-items: center; gap: .65rem; color: #64748b;
                          text-decoration: none; padding: .6rem .8rem; border-radius: .6rem;
                          margin-bottom: .2rem; font-size: .92rem; transition: all 0.2s ease; }
        .sidebar a.menu:hover { background: #eef2ff; color: #4f46e5; } /* Efek hover halus */
        
        /* Footer Sidebar (User Box) */
        .sidebar .footer { margin-top: auto; } /* margin-top: auto mendorong elemen ini ke paling bawah */
        .user-box { background: #eef2ff; border-radius: .8rem; padding: .8rem; }
        
        /* Avatar Bulat Otomatis */
        .avatar { width: 38px; height: 38px; border-radius: 50%; background: #6366f1; color: #fff;
                  display: inline-flex; align-items: center; justify-content: center; font-weight: 700; }
        
        /* Main Content: Diberi margin kiri 260px agar tidak tertutup sidebar */
        .main { margin-left: 260px; padding: 2rem; }
        
        /* Komponen Card & Icon Custom */
        .card { border: 1px solid #e9ecef; border-radius: .9rem; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .icon-circle { width: 42px; height: 42px; border-radius: 50%;
                       display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    </style>
</head>
<body>
    {{-- Variabel $role disimpan di sini agar tidak perlu menulis auth()->user()->role berulang-ulang di bawah --}}
    @php $role = auth()->user()->role; @endphp

    <aside class="sidebar">
        <div class="brand">
            <i class="bi bi-megaphone-fill"></i>
            <span>Sistem Pengaduan</span>
        </div>

        <nav>
            {{-- Route dinamis: Jika role='admin', akan ke 'admin.dashboard'. Jika 'customer', ke 'customer.dashboard' --}}
            <a class="menu" href="{{ route($role . '.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            {{-- ROLE-BASED ACCESS CONTROL (RBAC) DI UI --}}
            {{-- Menu ini HANYA muncul jika user yang login adalah admin --}}
            @if ($role == 'admin')
                <a class="menu" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> Kelola User
                </a>
                <a class="menu" href="{{ route('admin.kelola.pengaduan') }}">
                    <i class="bi bi-clipboard-data"></i> Kelola Pengaduan
                </a>
            @endif

            {{-- Menu ini HANYA muncul jika user yang login adalah customer --}}
            @if ($role == 'customer')
                <a class="menu" href="{{ route('customer.pengaduan.index') }}">
                    <i class="bi bi-megaphone"></i> Pengaduan Saya
                </a>
            @endif
        </nav>

        <div class="footer">
            <div class="user-box">
                <div class="d-flex align-items-center gap-2 mb-2">

                    {{-- AVATAR OTOMATIS: Mengambil huruf pertama nama user, lalu diubah jadi huruf kapital --}}
                    {{-- Contoh: "Syifaa" -> substr ambil "S" -> strtoupper jadi "S" --}}

                    <span class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                    <div>
                        <div class="small fw-semibold text-dark">{{ auth()->user()->nama }}</div>
                        {{-- ucfirst = Mengubah huruf pertama string jadi kapital (admin -> Admin) --}}
                        <span class="badge" style="background:#6366f1;">{{ ucfirst($role) }}</span>
                    </div>
                </div>
                
                {{-- KEAMANAN: Logout WAJIB menggunakan method POST, BUKAN link <a> biasa --}}
                {{-- Ini mencegah serangan CSRF dan mencegah user ter-logout secara tidak sengaja oleh web crawler --}}

                <form action="{{ route('logout') }}" method="POST">
                    @csrf {{"Fungsi @csrf adalah untuk Keamanan (Security). Jika kode itu dihapus, Laravel akan menolak request (muncul Error 419) untuk mencegah serangan yang disebut CSRF (Cross-Site Request Forgery)."*}}
                    <button class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main">
        {{-- FLASH MESSAGE: Menampilkan pesan sukses atau error yang dikirim dari Controller --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- @yield('content') = Tempat di mana isi dari halaman child (misal: dashboard.blade.php) akan disuntikkan --}}
        @yield('content')
    </main>

    {{-- Script Bootstrap JS (Wajib ada untuk fitur seperti dropdown atau alert dismiss) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>