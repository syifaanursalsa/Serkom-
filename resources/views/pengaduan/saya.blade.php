{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten di semua halaman --}}
@extends('layouts.app')

@section('title', 'Pengaduan Saya')

@section('content')
    {{-- HEADER HALAMAN: Menggunakan Flexbox untuk memisahkan judul dan tombol aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Pengaduan Saya</h4>
        
        {{-- CALL TO ACTION (CTA): Tombol utama dengan warna mencolok untuk mendorong user bertindak --}}
        <a href="{{ route('customer.pengaduan.create') }}" class="btn btn-sm text-white" style="background:#6366f1;">
            <i class="bi bi-plus-lg"></i> Buat Pengaduan
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- table-hover: Memberikan efek baris menyala saat di-hover, memudahkan mata membaca data --}}
            {{-- align-middle: Memastikan semua konten di dalam sel tabel rata tengah secara vertikal --}}
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Isi Pengaduan</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 2. LOOPING CERDAS DENGAN @FORELSE --}}
                    {{-- @forelse akan melakukan looping jika data ada, dan otomatis lari ke @empty jika data kosong --}}
                    @forelse ($pengaduan as $p)
                        <tr>
                            {{-- $loop->iteration = Fitur Blade otomatis untuk nomor urut (1, 2, 3...). 
                                 Jauh lebih rapi dan aman daripada membuat variabel counter manual ($no = 1) --}}
                            <td>{{ $loop->iteration }}</td>
                            
                            {{-- Carbon Formatting: Mengubah timestamp database menjadi format yang mudah dibaca --}}
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            
                            {{-- Mengambil data kategori langsung dari kolom string (sesuai desain arsitektur kita) --}}
                            <td><span class="badge" style="background:#6c757d;">{{ $p->kategori }}</span></td>
                            
                            {{-- 3. UX BEST PRACTICE: Str::limit --}}
                            {{-- Memotong teks maksimal 50 karakter + "...". 
                                 Ini SANGAT PENTING agar tinggi baris tabel tidak "meledak" jika user mengetik sangat panjang --}}
                            <td>{{ Str::limit($p->pengaduan, 50) }}</td>
                            
                            <td>
                                @if ($p->foto)
                                    {{-- asset('storage/') = Cara standar Laravel memanggil file upload.
                                         max-width: 70px = Mencegah gambar besar merusak lebar kolom tabel --}}
                                    <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto"
                                         style="max-width:70px; border-radius:.5rem;">
                                @else
                                    <span class="text-muted small">Tidak ada</span>
                                @endif
                            </td>
                            
                            <td>
                                {{-- Conditional Rendering: Memberikan warna badge berbeda agar status mudah dikenali secara visual --}}
                                @if ($p->status == 'menunggu')
                                    <span class="badge text-bg-warning">Menunggu</span>
                                @elseif ($p->status == 'diproses')
                                    <span class="badge text-bg-primary">Diproses</span>
                                @else
                                    <span class="badge text-bg-success">Selesai</span>
                                @endif
                            </td>
                            
                            <td>
                                {{-- Named Route dengan Parameter: Mengirim ID pengaduan ($p->id) ke halaman detail --}}
                                <a href="{{ route('customer.pengaduan.detail', $p->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        {{-- 4. EMPTY STATE (UX PENTING) --}}
                        {{-- colspan="7" harus sama dengan jumlah kolom <th> di atasnya agar tabel tidak rusak saat kosong --}}
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Kamu belum punya pengaduan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection