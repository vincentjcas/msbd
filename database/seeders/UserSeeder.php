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
            ['name' => 'Admin Demo', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Guru Demo', 'email' => 'guru@example.com', 'role' => 'guru'],
            ['name' => 'Siswa Demo', 'email' => 'siswa@example.com', 'role' => 'siswa'],
            ['name' => 'Pembina Demo', 'email' => 'pembina@example.com', 'role' => 'pembina'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'), // default password: password
                    'role' => $u['role'],
                ]
            );
        }
    }
}
