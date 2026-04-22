<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\ExpertTip;
use App\Models\Consultation;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends ApiController
{
    /**
     * لوحة معلومات المدير - Admin Dashboard
     * 
     * تعرض إحصائيات عامة للنظام
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_experts' => User::where('role', 'expert')->count(),
            'total_posts' => Post::count(),
            'total_consultations' => Consultation::count(),
            'total_crops' => Crop::count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'role', 'created_at']),
        ];

        return $this->successResponse($stats, 'Admin dashboard statistics retrieved successfully.');
    }

    /**
     * قائمة المستخدمين - Users List
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(20);

        return $this->successResponse($users, 'Users list retrieved successfully.');
    }

    /**
     * حذف مستخدم - Delete User
     */
    public function deleteUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return $this->errorResponse('You cannot delete your own account.', [], 403);
        }

        $user->delete();

        return $this->successResponse(null, 'User deleted successfully.');
    }

    /**
     * قائمة المنشورات - Posts List
     */
    public function posts(Request $request)
    {
        $posts = Post::with('user:id,name')->latest()->paginate(20);
        return $this->successResponse($posts, 'Posts list retrieved successfully.');
    }

    /**
     * حذف منشور - Delete Post
     */
    public function deletePost(Post $post)
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        
        $post->delete();

        return $this->successResponse(null, 'Post deleted successfully.');
    }

    /**
     * إضافة إعلان رسمي - Store Admin Announcement
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $imagePath,
            'type' => 'announcement',
        ]);

        return $this->successResponse($post, 'Announcement posted successfully.');
    }

    /**
     * إضافة تعليمات خبير - Store Expert Instruction (Admin)
     */
    public function storeInstruction(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $tip = ExpertTip::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $this->successResponse($tip, 'Instruction published successfully.');
    }
}
