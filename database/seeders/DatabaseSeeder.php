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
        // Create Test Farmer
        $user = \App\Models\User::factory()->create([
            'name' => 'Test Farmer',
            'email' => 'farmer@reefy.com',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        // Create Profile
        \App\Models\FarmerProfile::create([
            'user_id' => $user->id,
            'bio' => 'مزارع مجتهد أحب الأرض',
            'city' => 'Gaza',
            'experience_years' => 5,
            'address' => 'Gaza, Palestine',
        ]);

        // Create Crops
        \App\Models\Crop::factory(5)->create([
            'user_id' => $user->id
        ]);
    }
}
