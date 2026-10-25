<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    // DAFTAR KATEGORI HARDCODED
    private const KATEGORI = ['Infrastruktur', 'Pelayanan Publik', 'Keamanan', 'Kebersihan', 'Lainnya'];

    public function create()
    {
        return view('pengaduan.create', ['kategoris' => self::KATEGORI]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:' . implode(',', self::KATEGORI),
            'pengaduan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->hasFile('foto')
            ? $request->file('foto')->store('pengaduan', 'public')
            : null;

        Pengaduan::create([
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'pengaduan' => $request->pengaduan,
            'foto' => $path,
            'status' => 'menunggu',
        ]);

        return redirect()->route('customer.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function saya()
    {
        $pengaduan = Pengaduan::where('user_id', auth()->id())->latest()->get();
        return view('pengaduan.saya', compact('pengaduan'));
    }

    public function detail($id)
    {
        $pengaduan = Pengaduan::where('user_id', auth()->id())->findOrFail($id);
        return view('pengaduan.detail', compact('pengaduan'));
    }

    public function kelola()
    {
        $pengaduan = Pengaduan::with('user')->latest()->get();
        return view('kelola.index', compact('pengaduan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:menunggu,diproses,selesai']);
        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = $request->status;
        $pengaduan->save();
        // PERBAIKAN: tambah prefix admin.
        return redirect()->route('admin.kelola.pengaduan')->with('success', 'Status pengaduan diperbarui.');
    }
}