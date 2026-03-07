<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Consultation;
use App\Models\Crop;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $experts = User::where('role', 'expert')->get();
        $farmers = User::where('role', 'farmer')->get();

        foreach ($experts as $expert) {
            foreach (range(1, 10) as $index) {
                $farmer = $farmers->random();
                $crop = Crop::where('user_id', $farmer->id)->get()->random();

                Consultation::factory()->create([
                    'user_id' => $farmer->id,
                    'expert_id' => $expert->id,
                    'crop_id' => $crop->id,
                ]);
            }
        }
    }
}
