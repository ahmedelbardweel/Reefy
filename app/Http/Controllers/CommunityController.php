<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user']);
            }, 'likes'])
            ->latest()
            ->paginate(10);
            
        return view('community.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|string|in:post,question,inquiry,tip,poll',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community/posts', 'public');
        }

        Post::create([
            'user_id' => auth()->id(), // Will be null for guests which is fine now
            'content' => $request->content,
            'type' => $request->type,
            'image_path' => $imagePath
        ]);

        return back()->with('success', 'تم نشر مشاركتك في المجتمع!');
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user')
            ]);
        }

        return back()->with('success', 'تم إضافة تعليقك.');
    }

    public function toggleLike(Post $post)
    {
        $userId = auth()->id();
        $sessionId = request()->session()->getId();

        if ($userId) {
            $like = $post->likes()->where('user_id', $userId)->first();
            if ($like) {
                $like->delete();
                $status = 'unliked';
            } else {
                $post->likes()->create(['user_id' => $userId]);
                $status = 'liked';
            }
        } else {
            $like = $post->likes()->whereNull('user_id')->where('session_id', $sessionId)->first();
            if ($like) {
                $like->delete();
                $status = 'unliked';
            } else {
                $post->likes()->create(['session_id' => $sessionId]);
                $status = 'liked';
            }
        }

        if (request()->ajax()) {
            return response()->json(['status' => $status, 'likes_count' => $post->likes()->count()]);
        }

        return back();
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments' => function($query) {
            $query->whereNull('parent_id')->with(['user', 'replies.user']);
        }, 'likes']);
        
        return view('community.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return back()->with('success', 'تم حذف المنشور.');
    }
}
