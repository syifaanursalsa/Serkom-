@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
    <h4 class="mb-4">Tambah User Baru</h4>
    <div class="card" style="max-width:500px;"><div class="card-body">
        @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
        {{-- prefix admin. di route store --}}
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="customer" selected>Customer</option>
                </select>
            </div>
            <button class="btn text-white" style="background:#6366f1;">Simpan</button>
            {{-- prefix admin. di route batal --}}
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div></div>
@endsection