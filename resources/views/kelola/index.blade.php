{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten --}}
@extends('layouts.app')

@section('title', 'Kelola Pengaduan')

@section('content')
    <h4 class="mb-4">Kelola Pengaduan (Penanganan)</h4>
    
    {{-- card & table-responsive: Membuat tabel bisa di-scroll horizontal di layar HP (UX Mobile Friendly) --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                {{-- table-hover: Efek baris menyala saat kursor diarahkan, memudahkan admin membaca data --}}
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
                        {{-- @forelse = Looping cerdas. Jika data ada, dia loop. Jika kosong, dia langsung lari ke @empty --}}
                        @forelse ($pengaduan as $p)
                            <tr>
                                {{-- $loop->iteration = Fitur Blade otomatis untuk nomor urut (1, 2, 3...), 
                                     lebih rapi daripada membuat variabel $no = 1 manual --}}
                                <td>{{ $loop->iteration }}</td>
                                
                                {{-- ->format('d M Y') = Fitur Carbon (Library tanggal Laravel). 
                                     Mengubah format '2023-10-26 10:00:00' menjadi '26 Okt 2023' --}}
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                                
                                {{-- $p->user->nama = Memanggil relasi Eloquent (belongsTo) ke tabel users.
                                     ?? '-' = Null Coalescing Operator. Jika user dihapus, tampilkan '-' 
                                     agar tidak muncul error "Trying to get property of non-object" --}}
                                <td>{{ $p->user->nama ?? '-' }}</td>
                                
                                <td>
                                    <span class="badge" style="background:#6c757d;">{{ $p->kategori }}</span>
                                </td>
                                
                                {{-- Str::limit = Memotong teks jika terlalu panjang (maksimal 50 karakter + "...").
                                     Ini SANGAT PENTING untuk UX agar tinggi baris tabel tidak rusak/kepanjangan --}}
                                <td>{{ Str::limit($p->pengaduan, 50) }}</td>
                                
                                <td>
                                    @if ($p->foto)
                                        {{-- asset('storage/...') = Cara BENAR memanggil file upload di Laravel.
                                             Ini akan mengarah ke folder public/storage yang sudah di-link --}}
                                        <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto"
                                             style="max-width:70px; border-radius:.5rem;">
                                    @else
                                        <span class="text-muted small">Tidak ada</span>
                                    @endif
                                </td>
                                
                                <td>
                                    {{-- Conditional rendering untuk memberi warna badge berbeda sesuai status --}}
                                    @if ($p->status == 'menunggu')
                                        <span class="badge text-bg-warning">Menunggu</span>
                                    @elseif ($p->status == 'diproses')
                                        <span class="badge text-bg-primary">Diproses</span>
                                    @else
                                        <span class="badge text-bg-success">Selesai</span>
                                    @endif
                                </td>
                                
                                <td>
                                    {{-- FORM UPDATE STATUS (RESTful) --}}
                                    {{-- route('admin.kelola.status', $p->id) = Named route dengan parameter ID pengaduan --}}
                                    <form action="{{ route('admin.kelola.status', $p->id) }}" method="POST" class="d-flex gap-1">
                                        
                                        {{-- @csrf = WAJIB! Token keamanan untuk mencegah serangan CSRF --}}
                                        @csrf
                                        
                                        {{-- @method('PUT') = Method Spoofing. 
                                             HTML form hanya support GET/POST. Kita pakai ini untuk memberi tahu Laravel 
                                             bahwa request ini adalah UPDATE (sesuai standar RESTful API) --}}
                                        @method('PUT')
                                        
                                        <select name="status" class="form-select form-select-sm" style="width:auto;">
                                            {{-- @selected = Fitur Blade modern (Laravel 9+). 
                                                 Otomatis menambahkan atribut 'selected' jika kondisinya true. 
                                                 Jauh lebih rapi daripada menulis if-else di dalam <option> --}}
                                            <option value="menunggu" @selected($p->status == 'menunggu')>Menunggu</option>
                                            <option value="diproses" @selected($p->status == 'diproses')>Diproses</option>
                                            <option value="selesai" @selected($p->status == 'selesai')>Selesai</option>
                                        </select>
                                        
                                        <button class="btn btn-sm text-white" style="background:#6366f1;">Ubah</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- Tampilan jika database masih kosong (Belum ada data pengaduan) --}}
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada pengaduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection