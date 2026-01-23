<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SocialService
{
    /**
     * Create a new post in the community feed.
     * Triggered by: "نشر منشور جديد"
     * 
     * @param array $data
     * @param UploadedFile|null $image
     * @return Post
     */
    public function createPost(array $data, ?UploadedFile $image = null): Post
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('posts', 'public');
        }

        return Post::create([
            'user_id' => $data['user_id'] ?? auth()->id(),
            'content' => $data['content'],
            'image_path' => $imagePath,
            'type' => $data['type'] ?? 'general',
        ]);
    }

    /**
     * Like or unlike a post.
     */
    public function toggleLike(int $postId, int $userId)
    {
        $post = Post::findOrFail($postId);
        $like = $post->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            return ['status' => 'unliked'];
        }

        $post->likes()->create(['user_id' => $userId]);
        return ['status' => 'liked'];
    }
}
