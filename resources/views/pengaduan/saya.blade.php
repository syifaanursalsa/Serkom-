@extends('layouts.app')
@section('title', 'Pengaduan Saya')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Pengaduan Saya</h4>
        <a href="{{ route('customer.pengaduan.create') }}" class="btn btn-sm text-white" style="background:#6366f1;">+ Buat Pengaduan</a>
    </div>
    <div class="card"><div class="card-body">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr><th style="width:50px;">No</th><th>Tanggal</th><th>Kategori</th><th>Isi Pengaduan</th><th>Foto</th><th>Status</th><th style="width:90px;">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($pengaduan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                    {{-- kategori langsung string dari kolom tabel --}}
                    <td><span class="badge" style="background:#6c757d;">{{ $p->kategori }}</span></td>
                    <td>{{ Str::limit($p->pengaduan, 50) }}</td>
                    <td>
                        @if ($p->foto)
                            <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto"
                                 style="max-width:70px;border-radius:.5rem;">
                        @else
                            <span class="text-muted small">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if ($p->status == 'menunggu')
                            <span class="badge text-bg-warning">Menunggu</span>
                        @elseif ($p->status == 'diproses')
                            <span class="badge text-bg-primary">Diproses</span>
                        @else
                            <span class="badge text-bg-success">Selesai</span>
                        @endif
                    </td>
                    {{-- tombol detail menuju halaman detail --}}
                    <td>
                        <a href="{{ route('customer.pengaduan.detail', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Kamu belum punya pengaduan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
@endsection