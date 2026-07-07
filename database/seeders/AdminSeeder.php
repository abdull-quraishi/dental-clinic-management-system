<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
       public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'phone_number' => '0793520295',
            'role' => 'super_admin',
            'password' => Hash::make('admin12345'),
        ]);

        $superAdmin->assignRole('super_admin');

    }
}