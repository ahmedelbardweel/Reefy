<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\ExpertTip;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Delete a user and all their associated data.
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Standard Laravel delete will trigger cascades if defined in migration, 
        // otherwise we might need manual cleanup. 
        // For simplicity assuming standard foreign key cascades.
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a specific post.
     */
    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted successfully.');
    }

    /**
     * Store a community post published by Admin.
     */
    public function storeAdminPost(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $imagePath,
            'type' => 'announcement', // Mark as announcement
        ]);

        return back()->with('success', 'Announcement posted successfully.');
    }

    /**
     * Store an instruction (Expert Tip) published by Admin.
     */
    public function storeInstruction(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        ExpertTip::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Instruction published successfully.');
    }
}
