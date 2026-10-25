<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // hanya akun demo; kategori hardcoded di kode, tidak di-seed
        $users = [
            ['nama' => 'Administrator', 'email' => 'admin@gmail.com', 'password' => 'admin123', 'role' => 'admin'],
            ['nama' => 'Customer Satu', 'email' => 'customer@gmail.com', 'password' => 'customer123', 'role' => 'customer'],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                ['nama' => $u['nama'], 'password' => Hash::make($u['password']), 'role' => $u['role']]
            );
        }
    }
}