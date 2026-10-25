@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('register.proses') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-dark w-100">Daftar</button>
    </form>
    <p class="text-center small mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
@endsection
