<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder
{

    public function run(): void
    {

        User::updateOrCreate(

            [
                'email' => 'teknikairnav6@gmail.com'
            ],

            [

                'name' => 'Administrator AirNav Cabang Banjarmasin',

                'username' => 'adminairnav',

                'password' => Hash::make('password123'),

                'role' => 'admin',

            ]

        );

    }

}