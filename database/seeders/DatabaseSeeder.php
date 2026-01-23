<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@reefy.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Create Expert User
        $expert = \App\Models\User::factory()->create([
            'name' => 'Expert User',
            'email' => 'expert@reefy.com',
            'password' => bcrypt('password'),
            'role' => 'expert',
        ]);

        \App\Models\ExpertProfile::create([
            'user_id' => $expert->id,
            'specialization' => 'النباتات المنزلية والعناية بالتربة',
            'qualification' => 'دكتوراه في العلوم الزراعية',
            'is_verified' => true,
        ]);

        // 3. Create Test Farmer
        $farmer = \App\Models\User::factory()->create([
            'name' => 'Test Farmer',
            'email' => 'farmer@reefy.com',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        // Create Profile
        \App\Models\FarmerProfile::create([
            'user_id' => $farmer->id,
            'bio' => 'مزارع مجتهد أحب الأرض',
            'city' => 'Gaza',
            'experience_years' => 5,
            'address' => 'Gaza, Palestine',
        ]);

        // Create Crops
        \App\Models\Crop::factory(5)->create([
            'user_id' => $farmer->id
        ]);
    }
}
