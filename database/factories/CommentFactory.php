<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'content' => $this->faker->randomElement([
                'ما شاء الله، وفقك الله في حصادك.',
                'نعم جربتها وكانت ممتازة جداً، أنصحك بها.',
                'شكراً جزيلاً على هذه النصيحة القيمة.',
                'أتفق معك تماماً، جودة المياه هي الأساس.',
                'موضوع مهم جداً، ننتظر سماع آراء الخبراء هنا.'
            ]),
            'parent_id' => null,
        ];
    }
}
