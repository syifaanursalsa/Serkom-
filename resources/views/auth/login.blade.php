@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('login.proses') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-dark w-100">Masuk</button>
    </form>
    <p class="text-center small mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection