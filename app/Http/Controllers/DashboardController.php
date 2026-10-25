<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard', [
            'totalUser' => User::count(),               // semua user
            'totalPengaduan' => Pengaduan::count(),     // semua pengaduan
        ]);
    }

    public function customer()
    {
        return view('customer.dashboard', [
            'totalSaya' => Pengaduan::where('user_id', auth()->id())->count(), // milik sendiri
        ]);
    }
}