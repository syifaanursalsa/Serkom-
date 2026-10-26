{{-- 1. TEMPLATE INHERITANCE --}}
{{-- @extends = Mewarisi layout khusus autentikasi (layouts.auth). 
     Layout ini biasanya berbeda dengan layouts.app (tanpa sidebar/header), 
     fokus pada tampilan form yang bersih dan berada di tengah layar. --}}

@extends('layouts.auth')

{{-- Mengisi bagian <title> di layout utama agar judul tab browser menjadi "Login" --}}

@section('title', 'Login')

{{-- Memulai blok konten utama yang akan disuntikkan ke @yield('content') di layouts.auth --}}

@section('content')

    {{-- BAGIAN 1: PENANGANAN PESAN (FLASH MESSAGE & ERROR) --}}
    
    {{-- session('success') = Menangkap pesan sukses yang dikirim dari Controller (misal: setelah register berhasil) --}}

    @if (session('success'))

        {{-- alert-success = Kelas Bootstrap untuk kotak pesan berwarna hijau --}}

        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    {{-- $errors->any() = Mengecek apakah ada error validasi yang dikirim dari Controller (misal: password salah) --}}
    @if ($errors->any())
        {{-- $errors->first() = Hanya menampilkan pesan error PERTAMA yang terjadi agar tampilan tidak penuh --}}
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    {{-- BAGIAN 2: FORM LOGIN --}}

    
    {{-- action="{{ route('login.proses') }}" = Menggunakan Named Route, BUKAN URL hardcoded (/login). 
         Ini best practice agar jika URL berubah di web.php, kita tidak perlu mengubah kode di view ini. --}}
    <form action="{{ route('login.proses') }}" method="POST">
        
        {{-- @csrf = WAJIB ADA! (Cross-Site Request Forgery Token). 
             Ini menghasilkan token tersembunyi untuk memastikan form ini benar-benar dikirim dari website kita, 
             bukan dari website palsu/hacker yang mencoba mengirim data ke server kita. --}}
        @csrf

        <div class="mb-2">
            <label class="form-label">Email</label>
            {{-- value="{{ old('email') }}" = Fitur UX (User Experience). 
                 Jika user salah memasukkan password (error validasi), email yang sudah diketik TIDAK akan hilang, 
                 sehingga user tidak perlu mengetik ulang emailnya dari awal. --}}
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            {{-- type="password" = Menyamarkan input menjadi titik-titik (bullet) agar tidak mengintip (shoulder surfing) --}}
            <input type="password" name="password" class="form-control" required>
        </div>

        {{-- btn-dark = Tombol berwarna hitam/gelap. w-100 = width 100% (lebar penuh mengikuti container) --}}
        <button class="btn w-100" style="background-color: #0e0e10; color: white;">Masuk</button>
    </form>

    {{-- BAGIAN 3: LINK REGISTRASI --}}
  
    <p class="text-center small mt-3 mb-0">
        Belum punya akun? 
        {{-- route('register') = Mengarahkan ke halaman pendaftaran menggunakan Named Route --}}
        <a href="{{ route('register') }}">Daftar</a>
    </p>

{{-- Menutup blok konten --}}
@endsection