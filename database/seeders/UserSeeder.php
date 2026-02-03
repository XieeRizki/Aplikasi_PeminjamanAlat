<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
        ]);

        User::create([
            'username' => 'petugas',
            'password' => Hash::make('petugas123'),
            'level' => 'petugas',
        ]);

        User::create([
            'username' => 'peminjam',
            'password' => Hash::make('peminjam123'),
            'level' => 'peminjam',
        ]);
    }
}