{{-- 1. TEMPLATE INHERITANCE --}}
{{-- Mewarisi layout utama agar sidebar, header, dan footer tetap konsisten --}}
@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
    {{-- HEADER HALAMAN: Flexbox untuk memisahkan judul dan tombol aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data User</h4>
        
        {{-- CALL TO ACTION: Tombol tambah user dengan warna mencolok --}}
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm text-white" style="background:#6366f1;">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- table-hover & align-middle: Membuat tabel mudah dibaca dan rapi secara vertikal --}}
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 2. LOOPING CERDAS DENGAN @FORELSE --}}
                    @forelse ($users as $user)
                        <tr>
                            {{-- $loop->iteration: Fitur Blade otomatis untuk nomor urut (1, 2, 3...) --}}
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{-- Conditional Rendering: Memberikan warna badge berbeda agar role mudah dikenali --}}
                                @if ($user->role == 'admin')
                                    <span class="badge" style="background:#6366f1;">Admin</span>
                                @else
                                    <span class="badge" style="background:#0dcaf0; color:#000;">Customer</span>
                                @endif
                            </td>
                            <td>
                                {{-- TOMBOL EDIT: Menggunakan link <a> karena ini hanya operasi pembacaan (GET) --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                {{-- 3. TOMBOL HAPUS: KEAMANAN TINGKAT TINGGI (RESTful DELETE) --}}
                                {{-- WAJIB menggunakan <form> dengan method POST/DELETE, BUKAN link <a> --}}
                                {{-- Ini mencegah serangan CSRF dan mencegah user terhapus secara tidak sengaja oleh web crawler --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus user ini? Data tidak bisa dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- 4. EMPTY STATE: colspan="5" harus sama dengan jumlah kolom <th> agar tabel tidak rusak --}}
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection