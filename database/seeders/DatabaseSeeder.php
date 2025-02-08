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
        User::factory()->count(10)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '+71234567890',
            'birth_date' => '1990-01-01',
            'address' => 'Admin Street',
            'password' => bcrypt('06062001'),
        ]);
    }
}
