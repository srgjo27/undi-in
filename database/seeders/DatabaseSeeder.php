<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all the seeders for clean data
        $this->call([
            AdminSeeder::class,
            TestUserSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
