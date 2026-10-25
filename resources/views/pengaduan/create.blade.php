@extends('layouts.app')
@section('title', 'Buat Pengaduan')
@section('content')
    <h4 class="mb-4">Form Pengaduan Baru</h4>
    <div class="card" style="max-width:600px;"><div class="card-body">
        @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
        {{-- enctype multipart WAJIB untuk upload foto --}}
        <form action="{{ route('customer.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-2">
                <label class="form-label">Kategori</label>
                {{-- $kategoris = ARRAY STRING dari controller (bukan dari database) --}}
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k }}" @selected(old('kategori') == $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Isi Pengaduan</label>
                <textarea name="pengaduan" class="form-control" rows="4" required>{{ old('pengaduan') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Bukti (opsional, jpg/png maks 2MB)</label>
                <input type="file" name="foto" class="form-control">
            </div>
            <button class="btn text-white" style="background:#6366f1;">Kirim Pengaduan</button>
            <a href="{{ route('customer.pengaduan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div></div>
@endsection