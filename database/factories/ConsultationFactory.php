<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\User;
use App\Models\Crop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'expert_id' => User::factory(),
            'crop_id' => Crop::factory(),
            'subject' => $this->faker->randomElement([
                'استشارة حول اصفرار الأوراق',
                'سؤال بخصوص كمية الري المناسبة',
                'مكافحة حشرة المن في الطماطم',
                'كيفية تحسين جودة التربة',
                'توقيت الحصاد الأمثل للمحصول'
            ]),
            'question' => 'لدي مشكلة في محصولي حيث بدأت ألاحظ تغير في لون الأوراق وجفاف في الأطراف، ما هو الحل المناسب؟',
            'response' => 'يجب التأكد من انتظام الري وتجنب التعرض المباشر لأشعة الشمس الحارقة في وقت الظهيرة، كما يفضل إضافة سماد عضوي متوازن.',
            'status' => $this->faker->randomElement(['pending', 'responded']),
            'category' => $this->faker->randomElement(['تربة', 'آفات', 'ري', 'تسميد']),
        ];
    }
}
