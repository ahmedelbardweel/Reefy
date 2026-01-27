<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'crop_id' => Crop::factory(),
            'title' => $this->faker->randomElement([
                'ري المحصول',
                'إضافة سماد عضوي',
                'فحص الآفات الحشرية',
                'حصاد تجريبي للمحصول',
                'تغطية التربة لحمايتها',
                'تقليم الأغصان الزائدة',
                'فحص مستوى الرطوبة'
            ]),
            'type' => $this->faker->randomElement(['irrigation', 'fertilization', 'pest_control', 'harvest', 'maintenance']),
            'due_date' => $this->faker->dateTimeBetween('-1 week', '+1 month'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'notes' => 'يجب التأكد من جودة الأدوات المستخدمة والالتزام بالموعد المحدد لضمان أفضل نتائج.',
        ];
    }
}
