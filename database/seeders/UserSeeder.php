<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'nama_lengkap' => 'Admin Demo',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ],
            [
                'username' => 'guru',
                'nama_lengkap' => 'Guru Demo',
                'email' => 'guru@example.com',
                'role' => 'guru'
            ],
            [
                'username' => 'siswa',
                'nama_lengkap' => 'Siswa Demo',
                'email' => 'siswa@example.com',
                'role' => 'siswa'
            ],
            [
                'username' => 'pembina',
                'nama_lengkap' => 'Pembina Demo',
                'email' => 'pembina@example.com',
                'role' => 'pembina'
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'username' => $u['username'],
                    'nama_lengkap' => $u['nama_lengkap'],
                    'password' => Hash::make('password'), // default password: password
                    'role' => $u['role'],
                    'status_aktif' => true
                ]
            );
        }
    }
}
