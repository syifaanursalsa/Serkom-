@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <h4 class="mb-4">Edit User: {{ $user->nama }}</h4>
    <div class="card" style="max-width:500px;"><div class="card-body">
        @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
        {{-- prefix admin. di route update --}}
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diganti)</small></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="admin" @selected($user->role == 'admin')>Admin</option>
                    <option value="customer" @selected($user->role == 'customer')>Customer</option>
                </select>
            </div>
            <button class="btn text-white" style="background:#6366f1;">Simpan Perubahan</button>
            {{-- prefix admin. di route batal --}}
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div></div>
@endsection