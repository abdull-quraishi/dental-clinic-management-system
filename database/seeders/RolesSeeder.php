<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
       Role::firstOrCreate(['name' => 'super_admin']);
       Role::firstOrCreate(['name' => 'admin']);
       Role::firstOrCreate(['name' => 'general_doctor']);
       Role::firstOrCreate(['name' => 'filler_specialist_doctor']);
       Role::firstOrCreate(['name' => 'extractor_specialist_doctor']);
       Role::firstOrCreate(['name' => 'cleaner_specialist_doctor']);
       Role::firstOrCreate(['name' => 'root_canal_specialist_doctor']);
       Role::firstOrCreate(['name' => 'patient']);
    }
}
