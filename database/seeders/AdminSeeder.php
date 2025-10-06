<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Undi In',
            'email' => 'admin@undiin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '+62 123 456 789',
            'address' => 'Jakarta, Indonesia',
            'email_verified_at' => now(),
        ]);

        // Create sample seller
        User::create([
            'name' => 'John Seller',
            'email' => 'seller@undiin.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone_number' => '+62 987 654 321',
            'address' => 'Surabaya, Indonesia',
            'email_verified_at' => now(),
        ]);

        // Create sample buyer
        User::create([
            'name' => 'Jane Buyer',
            'email' => 'buyer@undiin.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone_number' => '+62 555 444 333',
            'address' => 'Bandung, Indonesia',
            'email_verified_at' => now(),
        ]);
    }
}
