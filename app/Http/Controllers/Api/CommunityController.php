<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageOptimization;

/**
 * كونترولر المجتمع API - Community API Controller
 * 
 * العلاقات:
 * - Post: belongsTo User
 * - Post: hasMany Comment
 * - Post: hasMany Like
 * 
 * هذا الكونترولر يوفر واجهات API للتفاعل مع المجتمع (المنشورات، التعليقات، الإعجابات)
 */
class CommunityController extends ApiController
{
    use ImageOptimization;
    /**
     * عرض قائمة المنشورات
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع المنشورات
     * - تحميل بيانات المستخدم صاحب المنشور
     * - تحميل عدد الإعجابات والتعليقات
     * - ترتيب المنشورات من الأحدث للأقدم
     * - تقسيم النتائج (Pagination)
     * - إضافة حقل 'is_liked' لمعرفة ما إذا كان المستخدم الحالي معجباً بالمنشور
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::with('user:id,name,role')
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(10);

        // إضافة حالة الإعجاب للمستخدم الحالي
        $posts->getCollection()->transform(function ($post) {
            $post->is_liked = $post->likes()->where('user_id', auth()->id())->exists();
            return $post;
        });

        return $this->successResponse($posts, 'Posts retrieved successfully.');
    }

    /**
     * إنشاء منشور جديد
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات (المحتوى، الصورة)
     * - رفع الصورة إن وجدت
     * - إنشاء المنشور وربطه بالمستخدم الحالي
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            // استخدام دالة التحسين بدلاً من الحفظ المباشر
            $path = $this->optimizeAndStore($request->file('image'), 'posts');
            $input['image_path'] = $path;
        }

        $post = Post::create($input);
        
        // إعادة تحميل المستخدم للعرض
        $post->load('user:id,name,role');

        return $this->successResponse($post, 'Post created successfully.');
    }

    /**
     * عرض تفاصيل منشور معين مع التعليقات
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Post::with(['user:id,name,role', 'comments.user:id,name,role'])
            ->withCount(['likes', 'comments'])
            ->find($id);

        if (is_null($post)) {
            return $this->errorResponse('Post not found.');
        }

        $post->is_liked = $post->likes()->where('user_id', auth()->id())->exists();

        return $this->successResponse($post, 'Post retrieved successfully.');
    }

    /**
     * الإعجاب بمنشور (Like/Unlike)
     * 
     * تقوم هذه الدالة بـ:
     * - التبديل بين الإعجاب وإلغاء الإعجاب (Toggle)
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->errorResponse('Post not found.');
        }

        $user = auth()->user();
        $existingLike = $post->likes()->where('user_id', $user->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Post unliked successfully.';
            $isLiked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id()]);
            $message = 'Post liked successfully.';
            $isLiked = true;
        }

        return $this->successResponse(['is_liked' => $isLiked, 'likes_count' => $post->likes()->count()], $message);
    }

    /**
     * إضافة تعليق على منشور
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, $id)
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return $this->errorResponse('Post not found.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        
        $comment->load('user:id,name,role');

        return $this->successResponse($comment, 'Comment added successfully.');
    }
}
