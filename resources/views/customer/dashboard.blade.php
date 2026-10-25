@extends('layouts.app')
@section('title', 'Dashboard Customer')
@section('content')
    <h4 class="mb-1 fw-semibold">Halo, {{ auth()->user()->nama }} 👋</h4>
    <p class="text-muted mb-4">Selamat datang di dashboard pengaduan.</p>

    <div class="card p-4" style="max-width:450px;">
        <div class="d-flex align-items-center gap-3">
            <span class="icon-circle" style="background:#eef2ff;color:#4f46e5;">
                <i class="bi bi-megaphone-fill"></i>
            </span>
            <div>
                <small class="text-muted d-block">Pengaduan Saya</small>
                <h4 class="mb-0">{{ $totalSaya }}</h4>
            </div>
        </div>
        <a href="{{ route('customer.pengaduan.create') }}" class="btn btn-sm text-white mt-3"
           style="background:#6366f1;">+ Buat Pengaduan Baru</a>
    </div>
@endsection