<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat user dengan level 'admin'
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('00000000'), // Ganti 'password' dengan password yang diinginkan
            'level' => 'admin'
        ]);

        // Buat user dengan level 'user'
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('00000000'), // Ganti 'password' dengan password yang diinginkan
            'level' => 'user'
        ]);
    }
}
