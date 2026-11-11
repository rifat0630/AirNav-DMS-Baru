<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Teknik CNS/A
        User::updateOrCreate(
            ['email' => 'admin@airnav.id'],
            [
                'name' => 'Admin Teknik CNS/A',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Manager Teknik (Mantek)
        User::updateOrCreate(
            ['email' => 'mantek@airnav.id'],
            [
                'name' => 'Manager Teknik CNS/A',
                'username' => 'mantek',
                'password' => Hash::make('password123'),
                'role' => 'mantek',
            ]
        );
    }
}