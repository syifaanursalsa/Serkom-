@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
    <h4 class="mb-1 fw-semibold">Halo, {{ auth()->user()->nama }} 👋</h4>
    <p class="text-muted mb-4">Ringkasan data sistem pengaduan.</p>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="icon-circle" style="background:#eef2ff;color:#4f46e5;">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <div>
                        <small class="text-muted d-block">Total User</small>
                        <h4 class="mb-0">{{ $totalUser }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="icon-circle" style="background:#fff7ed;color:#ea580c;">
                        <i class="bi bi-megaphone-fill"></i>
                    </span>
                    <div>
                        <small class="text-muted d-block">Total Pengaduan</small>
                        <h4 class="mb-0">{{ $totalPengaduan }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection