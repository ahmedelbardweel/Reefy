<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * كونترولر المجتمع - Community Controller
 * 
 * العلاقات:
 * - Post (المنشورات): hasMany مع User, Comments, Likes
 * - Comment (التعليقات): belongsTo Post, User ولها parent_id للردود
 * - Like (الإعجابات): belongsTo Post ويمكن أن تكون للمستخدمين المسجلين أو الزوار (عبر session_id)
 */
class CommunityController extends Controller
{
    /**
     * عرض قائمة المنشورات
     * 
     * تقوم هذه الدالة بـ:
     * - جلب جميع المنشورات من قاعدة البيانات
     * - تحميل علاقات: المستخدم صاحب المنشور، التعليقات الرئيسية (بدون parent_id) مع الردود، والإعجابات
     * - ترتيب المنشورات من الأحدث للأقدم
     * - تقسيم النتائج إلى صفحات (10 منشورات في كل صفحة)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب المنشورات مع علاقاتها
        $posts = Post::with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user']);
            }, 'likes'])
            ->latest()
            ->paginate(10);
            
        return view('community.index', compact('posts'));
    }

    /**
     * إنشاء منشور جديد
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات المدخلة (المحتوى، النوع، الصورة إن وجدت)
     * - رفع الصورة إلى مجلد community/posts إن وجدت
     * - حفظ المنشور في قاعدة البيانات مع ربطه بالمستخدم الحالي (أو null للزوار)
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * أنواع المنشورات: post (منشور عادي), question (سؤال), inquiry (استفسار), tip (نصيحة), poll (استطلاع)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|string|in:post,question,inquiry,tip,poll',
            'image' => 'nullable|image|max:2048'
        ]);

        // معالجة الصورة إن وجدت
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community/posts', 'public');
        }

        // إنشاء المنشور
        Post::create([
            'user_id' => auth()->id(), // سيكون null للزوار
            'content' => $request->content,
            'type' => $request->type,
            'image_path' => $imagePath
        ]);

        return back()->with('success', 'تم نشر مشاركتك في المجتمع!');
    }

    /**
     * إضافة تعليق على منشور
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات (المحتوى و parent_id للردود)
     * - إنشاء تعليق جديد مرتبط بالمنشور المحدد
     * - parent_id يحدد إذا كان التعليق رد على تعليق آخر
     * - إرجاع JSON response للطلبات AJAX أو redirect للطلبات العادية
     * 
     * العلاقة: Comment belongsTo Post و User، ولها replies (تعليقات فرعية)
     * 
     * @param Request $request
     * @param Post $post المنشور المراد التعليق عليه
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, Post $post)
    {
        // التحقق من البيانات
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // إنشاء التعليق
        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        // للطلبات AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user')
            ]);
        }

        return back()->with('success', 'تم إضافة تعليقك.');
    }

    /**
     * إضافة/إلغاء الإعجاب على منشور
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من حالة الإعجاب للمستخدم الحالي (مسجل أو زائر)
     * - للمستخدمين المسجلين: تستخدم user_id لتتبع الإعجابات
     * - للزوار: تستخدم session_id لتتبع الإعجابات
     * - إذا كان المستخدم قد أعجب مسبقاً: يتم حذف الإعجاب (unlike)
     * - إذا لم يكن قد أعجب: يتم إضافة إعجاب جديد (like)
     * - إرجاع JSON response للطلبات AJAX مع الحالة وعدد الإعجابات
     * 
     * العلاقة: Like belongsTo Post ولها user_id (nullable) أو session_id
     * 
     * @param Post $post المنشور المراد الإعجاب به
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleLike(Post $post)
    {
        $userId = auth()->id();
        $sessionId = request()->session()->getId();

        // معالجة المستخدمين المسجلين
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
            // معالجة الزوار باستخدام session
            $like = $post->likes()->whereNull('user_id')->where('session_id', $sessionId)->first();
            if ($like) {
                $like->delete();
                $status = 'unliked';
            } else {
                $post->likes()->create(['session_id' => $sessionId]);
                $status = 'liked';
            }
        }

        // للطلبات AJAX
        if (request()->ajax()) {
            return response()->json(['status' => $status, 'likes_count' => $post->likes()->count()]);
        }

        return back();
    }

    /**
     * عرض تفاصيل منشور معين
     * 
     * تقوم هذه الدالة بـ:
     * - تحميل المنشور المحدد مع علاقاته (المستخدم، التعليقات، الإعجابات)
     * - تحميل التعليقات الرئيسية فقط (بدون parent_id) مع الردود والمستخدمين
     * - عرض صفحة تفاصيل المنشور
     * 
     * @param Post $post المنشور المراد عرضه
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        // تحميل العلاقات
        $post->load(['user', 'comments' => function($query) {
            $query->whereNull('parent_id')->with(['user', 'replies.user']);
        }, 'likes']);
        
        return view('community.show', compact('post'));
    }

    /**
     * حذف منشور
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من أن المستخدم الحالي هو صاحب المنشور (Authorization)
     * - حذف صورة المنشور من التخزين إن وجدت
     * - حذف المنشور من قاعدة البيانات (سيتم حذف التعليقات والإعجابات تلقائياً بسبب cascade)
     * - إعادة التوجيه مع رسالة نجاح
     * 
     * @param Post $post المنشور المراد حذفه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // التحقق من الصلاحية
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        // حذف الصورة من التخزين
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        // حذف المنشور
        $post->delete();

        return back()->with('success', 'تم حذف المنشور.');
    }
}
