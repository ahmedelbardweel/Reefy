<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->randomElement([
                'اليوم بدأت موسم حصاد الطماطم، النتائج مبشرة جداً بفضل الله.',
                'هل لدى أحدكم تجربة مع هذا النوع من الأسمدة العضوية؟',
                'نصيحة للمزارعين: تأكدوا من جودة المياه قبل بدء الري المكثف.',
                'سعيد جداً بالانضمام إلى هذه المنصة الرائعة لتبادل الخبرات الزراعية.',
                'ما هي أفضل طريقة للتعامل مع جفاف التربة في فصل الصيف؟'
            ]),
            'image_path' => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?q=80&w=1000&auto=format&fit=crop',
            'type' => $this->faker->randomElement(['general', 'question', 'advice']),
        ];
    }
}
