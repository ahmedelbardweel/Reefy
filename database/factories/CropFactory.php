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
        $crops = [
            ['name' => 'طماطم', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'خيار', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1449339854873-750e6913301b?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'بطاطس', 'type' => 'خضروات', 'image' => 'https://images.unsplash.com/photo-1518977676601-b53f02bad6d5?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'قمح', 'type' => 'حبوب', 'image' => 'https://images.unsplash.com/photo-1501430043585-6101166661a3?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'ذرة', 'type' => 'حبوب', 'image' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'فراولة', 'type' => 'فاكهة', 'image' => 'https://images.unsplash.com/photo-1464960350423-95c65503e047?q=80&w=1000&auto=format&fit=crop'],
            ['name' => 'زيتون', 'type' => 'فاكهة', 'image' => 'https://images.unsplash.com/photo-1518843875459-f738682238a6?q=80&w=1000&auto=format&fit=crop'],
        ];

        $crop = $this->faker->randomElement($crops);

        return [
            'name' => $crop['name'],
            'type' => $crop['type'],
            'image_path' => $crop['image'],
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
