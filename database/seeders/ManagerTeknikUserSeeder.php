<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerTeknikUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'mantekbjm@airnav.co.id'],
            [
                'name' => 'Manager Teknik',
                'username' => 'mantekbjm',
                'password' => Hash::make('teknikberkelas2026'),
                'role' => 'mantek',
                'email_verified_at' => now(),
            ]
        );
    }
}