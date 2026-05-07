<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name'  => 'System',
            'last_name'   => 'Admin',
            'role'        => 'admin',
            'username'    => 'admin',
            'password'    => Hash::make('admin123'),
            'is_active'   => true,
        ]);

        User::create([
            'first_name'  => 'Alaa',
            'last_name'   => 'Khledj',
            'role'        => 'receptionist',
            'username'    => 'alaa.k',
            'password'    => Hash::make('alaa123'),
            'is_active'   => true,
        ]);

        User::create([
            'first_name'  => 'Raounak',
            'last_name'   => 'Abbad',
            'role'        => 'biologist',
            'username'    => 'raounak.a',
            'password'    => Hash::make('raounak123'),
            'is_active'   => true,
        ]);

        User::create([
            'first_name'  => 'Imene',
            'last_name'   => 'Bendouha',
            'role'        => 'doctor',
            'username'    => 'dr.imene',
            'password'    => Hash::make('imene123'),
            'is_active'   => true,
        ]);
    }
}