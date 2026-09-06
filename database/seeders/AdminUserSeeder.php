<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Membuat / memperbarui akun Administrator AirNav DMS.
     */
    public function run(): void
    {
        $email = env(
            'AIRNAV_ADMIN_EMAIL',
            'teknikairnav6@gmail.com'
        );

        $password = env(
            'AIRNAV_ADMIN_PASSWORD',
            'teknikairnavbanjarmasin1'
        );

        $user = User::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => 'Administrator AirNav Cabang Banjarmasin',
                'password' => Hash::make($password),
            ]
        );

        $this->command->info(
            'Admin AirNav DMS berhasil dibuat/diperbarui.'
        );

        $this->command->info(
            'Email: ' . $user->email
        );
    }
}