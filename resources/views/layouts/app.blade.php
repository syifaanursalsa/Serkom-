<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Pengaduan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #eef2f7; }
        .sidebar { width: 260px; background: #ffffff; border-right: 1px solid #e5e7eb;
                   position: fixed; top: 0; bottom: 0; left: 0;
                   display: flex; flex-direction: column; padding: 1.25rem 1rem; }
        .sidebar .brand { display: flex; align-items: center; gap: .6rem;
                          padding: 0 .25rem 1rem; border-bottom: 1px solid #eef0f3; margin-bottom: 1rem; }
        .sidebar .brand i { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
                            padding: .5rem .6rem; border-radius: .7rem; }
        .sidebar .brand span { font-weight: 700; color: #1f2937; }
        .sidebar a.menu { display: flex; align-items: center; gap: .65rem; color: #64748b;
                          text-decoration: none; padding: .6rem .8rem; border-radius: .6rem;
                          margin-bottom: .2rem; font-size: .92rem; }
        .sidebar a.menu:hover { background: #eef2ff; color: #4f46e5; }
        .sidebar .footer { margin-top: auto; }
        .user-box { background: #eef2ff; border-radius: .8rem; padding: .8rem; }
        .avatar { width: 38px; height: 38px; border-radius: 50%; background: #6366f1; color: #fff;
                  display: inline-flex; align-items: center; justify-content: center; font-weight: 700; }
        .main { margin-left: 260px; padding: 2rem; }
        .card { border: 1px solid #e9ecef; border-radius: .9rem; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .icon-circle { width: 42px; height: 42px; border-radius: 50%;
                       display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    </style>
</head>
<body>
    @php $role = auth()->user()->role; @endphp

    <aside class="sidebar">
        <div class="brand">
            <i class="bi bi-megaphone-fill"></i>
            <span>Sistem Pengaduan</span>
        </div>

               <nav>
            <a class="menu" href="{{ route($role . '.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if ($role == 'admin')
                <a class="menu" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> Kelola User
                </a>
                <a class="menu" href="{{ route('admin.kelola.pengaduan') }}">
                    <i class="bi bi-clipboard-data"></i> Kelola Pengaduan
                </a>
            @endif

            @if ($role == 'customer')
                <a class="menu" href="{{ route('customer.pengaduan.index') }}">
                    <i class="bi bi-megaphone"></i> Pengaduan Saya
                </a>
            @endif
        </nav>

        <div class="footer">
            <div class="user-box">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                    <div>
                        <div class="small fw-semibold text-dark">{{ auth()->user()->nama }}</div>
                        <span class="badge" style="background:#6366f1;">{{ ucfirst($role) }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>