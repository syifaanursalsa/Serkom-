@extends('layouts.app')
@section('title', 'Detail Pengaduan')
@section('content')
    <h4 class="mb-4">Detail Pengaduan</h4>
    <div class="card" style="max-width:700px;"><div class="card-body">
        {{-- baris info: tanggal, kategori, status --}}
        <div class="d-flex gap-2 mb-3">
            <span class="badge text-bg-secondary">{{ $pengaduan->created_at->format('d M Y') }}</span>
            <span class="badge" style="background:#6366f1;">{{ $pengaduan->kategori }}</span>
            @if ($pengaduan->status == 'menunggu')
                <span class="badge text-bg-warning">Menunggu</span>
            @elseif ($pengaduan->status == 'diproses')
                <span class="badge text-bg-primary">Diproses</span>
            @else
                <span class="badge text-bg-success">Selesai</span>
            @endif
        </div>

        <h6 class="text-muted">Isi Pengaduan</h6>
        {{-- isi lengkap tanpa dipotong --}}
        <p style="white-space: pre-line;">{{ $pengaduan->pengaduan }}</p>

        <h6 class="text-muted">Foto Bukti</h6>
        @if ($pengaduan->foto)
            {{-- foto ukuran besar --}}
            <img src="{{ asset('storage/' . $pengaduan->foto) }}" alt="Foto Bukti"
                 style="max-width:100%;border-radius:.6rem;">
        @else
            <p class="text-muted small">Tidak ada foto bukti.</p>
        @endif

        <a href="{{ route('customer.pengaduan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div></div>
@endsection