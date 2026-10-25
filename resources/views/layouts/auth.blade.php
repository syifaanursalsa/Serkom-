{{-- kerangka bersama halaman login & register --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Pengaduan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container" style="max-width: 420px; margin-top: 90px;">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center fw-bold">Sistem Pengaduan</div>
            <div class="card-body">
                @yield('content')   {{-- isi login/register masuk sini --}}
            </div>
        </div>
    </div>
</body>
</html>