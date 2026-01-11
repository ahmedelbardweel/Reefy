<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crop>
 */
class CropFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['طماطم', 'خيار', 'بطاطس', 'قمح', 'ذرة']),
            'type' => $this->faker->randomElement(['خضروات', 'حبوب', 'فاكهة']),
            'area' => $this->faker->numberBetween(1, 50),
            'soil_type' => $this->faker->randomElement(['طينية', 'رملية', 'طمية']),
            'irrigation_method' => $this->faker->randomElement(['تنقيط', 'رش', 'غمر']),
            'seed_source' => 'محلي',
            'yield_estimate' => $this->faker->numberBetween(100, 1000),
            'planting_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'expected_harvest_date' => $this->faker->dateTimeBetween('now', '+4 months'),
            'status' => 'growing',
            'growth_percentage' => $this->faker->numberBetween(10, 80),
            'notes' => $this->faker->sentence,
        ];
    }
}
