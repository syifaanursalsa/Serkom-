<?php

// Namespace = "alamat folder" file ini agar class tidak bentrok dengan class lain yang namanya sama
namespace App\Http\Controllers;

// Import model Pengaduan: jembatan PHP untuk mengakses tabel pengaduan di database
use App\Models\Pengaduan;

// Import class Request: pembungkus semua input yang dikirim dari form
use Illuminate\Http\Request;

// Class PengaduanController mewarisi class Controller bawaan Laravel
// Semua method di dalam class ini = "otak" yang menangani alur CRUD pengaduan
// (Create oleh customer, Read oleh customer & admin, Update status oleh admin)
class PengaduanController extends Controller
{
    // ============ DAFTAR KATEGORI HARDCODED ============
    // private const = konstanta privat (hanya bisa diakses di dalam class ini)
    // Kategori TIDAK disimpan di database, tapi di-hardcode di kode (array statis)
    // Keunggulan: simpel, tidak perlu tabel master, tidak perlu CRUD kategori
    // Kalau mau tambah/kurangi pilihan, edit array ini saja
    private const KATEGORI = ['Infrastruktur', 'Pelayanan Publik', 'Keamanan', 'Kebersihan', 'Lainnya'];

    // ============ METHOD 1: TAMPILKAN FORM BUAT PENGADUAN (CUSTOMER) ============
    // Dipanggil saat customer buka URL GET /customer/pengaduan/create
    public function create()
    {
        // Kirim array KATEGORI ke view sebagai variabel $kategoris
        // self::KATEGORI = cara akses konstanta di dalam class yang sama
        // Di view, $kategoris akan dipakai untuk mengisi dropdown <select>
        return view('pengaduan.create', ['kategoris' => self::KATEGORI]);
    }

    // ============ METHOD 2: SIMPAN PENGADUAN BARU (CUSTOMER) ============
    // Dipanggil saat customer klik "Kirim Pengaduan" di form (POST /customer/pengaduan)
    // Parameter $request = objek berisi semua input dari form (kategori, pengaduan, foto)
    public function store(Request $request)
    {
        // Validasi input SEBELUM diproses
        $request->validate([
            // kategori: wajib diisi + nilainya harus ADA di daftar KATEGORI
            // implode(',', self::KATEGORI) = gabungkan array jadi string: "Infrastruktur,Pelayanan Publik,..."
            // aturan in:Infrastruktur,Pelayanan Publik,... = nilai harus salah satu dari daftar itu
            'kategori' => 'required|in:' . implode(',', self::KATEGORI),

            // pengaduan: wajib diisi + harus berupa string (teks)
            'pengaduan' => 'required|string',

            // foto: boleh kosong (nullable) + kalau ada harus gambar (jpg/jpeg/png) + maks 2MB (2048 KB)
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Logika upload foto dengan ternary operator (if-else singkat)
        // $request->hasFile('foto') = cek apakah user upload file foto
        // Kalau YA: simpan file ke folder storage/app/public/pengaduan, return path-nya
        //   store('pengaduan', 'public') = simpan ke disk 'public' di folder 'pengaduan'
        //   Laravel otomatis generate nama file unik (misal: aB3x9K.jpg)
        // Kalau TIDAK: $path = null (tidak ada foto)
        $path = $request->hasFile('foto')
            ? $request->file('foto')->store('pengaduan', 'public')
            : null;

        // Pengaduan::create = INSERT baris baru ke tabel pengaduan
        Pengaduan::create([
            'user_id' => auth()->id(),         // otomatis = ID customer yang sedang login
            'kategori' => $request->kategori,  // ambil input kategori dari form
            'pengaduan' => $request->pengaduan, // ambil isi keluhan dari form
            'foto' => $path,                   // path file foto (atau null)
            'status' => 'menunggu',            // status awal selalu 'menunggu' (default alur)
        ]);

        // Redirect ke daftar pengaduan milik customer + flash message sukses
        return redirect()->route('customer.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim!');
    }

    // ============ METHOD 3: DAFTAR PENGADUAN MILIK CUSTOMER (CUSTOMER) ============
    // Dipanggil saat customer buka URL GET /customer/pengaduan
    public function saya()
    {
        // Query: ambil semua pengaduan yang user_id-nya = ID customer yang login
        // where('user_id', auth()->id()) = filter: hanya milik customer ini
        // latest() = urutkan berdasarkan created_at DESC (terbaru di atas)
        // get() = eksekusi query, return collection (array objek)
        $pengaduan = Pengaduan::where('user_id', auth()->id())->latest()->get();

        // compact('pengaduan') = shortcut mengirim variabel ke view
        // Sama dengan: ['pengaduan' => $pengaduan]
        return view('pengaduan.saya', compact('pengaduan'));
    }

    // ============ METHOD 4: DETAIL SATU PENGADUAN (CUSTOMER) ============
    // Dipanggil saat customer klik tombol "Detail" (GET /customer/pengaduan/{id})
    // Parameter $id = ID pengaduan yang diambil dari URL
    public function detail($id)
    {
        // AMANAN PENTING: filter user_id DULU baru findOrFail
        // Biar customer tidak bisa intip pengaduan milik customer lain dengan ubah ID di URL
        // where('user_id', auth()->id()) = pastikan milik customer yang login
        // findOrFail($id) = cari by ID; kalau tidak ada atau bukan miliknya -> error 404
        $pengaduan = Pengaduan::where('user_id', auth()->id())->findOrFail($id);

        return view('pengaduan.detail', compact('pengaduan'));
    }

    // ============ METHOD 5: DAFTAR SEMUA PENGADUAN (ADMIN) ============
    // Dipanggil saat admin buka URL GET /admin/kelola-pengaduan
    public function kelola()
    {
        // with('user') = eager loading: muat relasi user SEKALIGUS
        // Tanpa eager loading: N+1 query problem (tiap pengaduan = 1 query user terpisah)
        // Dengan eager loading: 1 query pengaduan + 1 query user (jauh lebih cepat)
        // latest() = urutkan terbaru di atas
        $pengaduan = Pengaduan::with('user')->latest()->get();

        return view('kelola.index', compact('pengaduan'));
    }

    // ============ METHOD 6: UBAH STATUS PENGADUAN (ADMIN) ============
    // Dipanggil saat admin klik tombol "Ubah" di form select status (PUT /admin/kelola-pengaduan/{id}/status)
    // Parameter $id = ID pengaduan yang akan diubah statusnya
    public function updateStatus(Request $request, $id)
    {
        // Validasi: status wajib diisi dan harus salah satu dari 3 pilihan yang diizinkan
        $request->validate(['status' => 'required|in:menunggu,diproses,selesai']);

        // findOrFail($id) = cari pengaduan by ID; kalau tidak ada -> error 404
        $pengaduan = Pengaduan::findOrFail($id);

        // Timpa nilai status dengan nilai baru yang dipilih admin
        $pengaduan->status = $request->status;

        // save() = simpan perubahan ke database (query UPDATE)
        $pengaduan->save();

        // Redirect ke halaman kelola pengaduan + flash message sukses
        return redirect()->route('admin.kelola.pengaduan')->with('success', 'Status pengaduan diperbarui.');
    }
}