<?php

namespace Database\Factories;

use App\Models\ExpertTip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertTipFactory extends Factory
{
    protected $model = ExpertTip::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement([
                'أفضل الممارسات لزيادة الإنتاجية',
                'كيفية التعامل مع التغيرات المناخية',
                'دليلك الشامل للتسميد العضوي',
                'طرق مبتكرة لتوفير مياه الري',
                'نصائح ذهبية لموسم الحصاد'
            ]),
            'content' => 'نصيحة ذهبية اليوم تركز على أهمية مراقبة المحصول يومياً في الصباح الباكر، حيث يسهل اكتشاف الآفات قبل انتشارها.',
        ];
    }
}
