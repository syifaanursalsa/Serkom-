@extends('layouts.app')
@section('title', 'Kelola Pengaduan')
@section('content')
    <h4 class="mb-4">Kelola Pengaduan (Penanganan)</h4>
    <div class="card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>Pengadu</th>
                        <th>Kategori</th>
                        <th>Isi Pengaduan</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th style="width:180px;">Penanganan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($pengaduan as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td>{{ $p->user->nama ?? '-' }}</td>
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
                        <td>
                            {{-- prefix admin. di route kelola.status --}}
                            <form action="{{ route('admin.kelola.status', $p->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" style="width:auto;">
                                    <option value="menunggu" @selected($p->status == 'menunggu')>Menunggu</option>
                                    <option value="diproses" @selected($p->status == 'diproses')>Diproses</option>
                                    <option value="selesai" @selected($p->status == 'selesai')>Selesai</option>
                                </select>
                                <button class="btn btn-sm text-white" style="background:#6366f1;">Ubah</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada pengaduan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
@endsection