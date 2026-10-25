<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,customer',
        ]);
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        // PERBAIKAN: tambah prefix admin.
        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,customer',
        ]);
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        // PERBAIKAN: tambah prefix admin.
        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == auth()->id()) {
            // PERBAIKAN: tambah prefix admin.
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        // PERBAIKAN: tambah prefix admin.
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}