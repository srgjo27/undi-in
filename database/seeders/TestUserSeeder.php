<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Test Seller',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
            'role' => 'seller',
            'phone_number' => '081234567890',
            'address' => 'Jakarta, Indonesia',
        ]);

        \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone_number' => '081234567891',
            'address' => 'Jakarta, Indonesia',
        ]);
    }
}
