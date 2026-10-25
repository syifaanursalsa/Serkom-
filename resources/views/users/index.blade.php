@extends('layouts.app')
@section('title', 'Kelola User')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data User</h4>
        {{-- prefix admin. di route create --}}
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm text-white" style="background:#6366f1;">+ Tambah User</a>
    </div>
    <div class="card"><div class="card-body">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr><th style="width:50px;">No</th><th>Nama</th><th>Email</th><th>Role</th><th style="width:160px;">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->role == 'admin')
                            <span class="badge" style="background:#6366f1;">Admin</span>
                        @else
                            <span class="badge" style="background:#0dcaf0;color:#000;">Customer</span>
                        @endif
                    </td>
                    <td>
                        {{-- prefix admin. di route edit --}}
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        {{-- prefix admin. di route destroy --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada data user.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
@endsection