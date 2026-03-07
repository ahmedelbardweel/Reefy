<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Crop;
use App\Models\Task;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = User::where('role', 'farmer')->get();

        foreach ($farmers as $farmer) {
            // Create 10 crops for each farmer
            Crop::factory(10)->create([
                'user_id' => $farmer->id
            ])->each(function ($crop) {
                // For each crop, create some tasks
                Task::factory(rand(3, 7))->create([
                    'crop_id' => $crop->id
                ]);
            });
        }
    }
}
