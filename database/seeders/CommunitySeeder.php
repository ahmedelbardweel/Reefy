<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\ExpertTip;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $experts = User::where('role', 'expert')->get();

        // 1. Create Posts
        foreach ($users as $user) {
            Post::factory(rand(2, 5))->create([
                'user_id' => $user->id
            ])->each(function ($post) use ($users) {
                // 2. Create Comments for each post
                Comment::factory(rand(1, 5))->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id
                ]);

                // 3. Create Likes for each post
                foreach (range(1, rand(0, 10)) as $index) {
                    Like::firstOrCreate([
                        'post_id' => $post->id,
                        'user_id' => $users->random()->id,
                    ], [
                        'session_id' => null,
                    ]);
                }
            });
        }

        // 4. Create Expert Tips
        foreach ($experts as $expert) {
            ExpertTip::factory(5)->create([
                'user_id' => $expert->id
            ]);
        }
    }
}
