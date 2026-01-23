<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * كونترولر المصادقة API - Auth Controller
 * 
 * هذا الكونترولر مسؤول عن عمليات المصادقة لـ API (للتطبيق الأندرويد):
 * - التسجيل (register)
 * - تسجيل الدخول (login)
 * - تسجيل الخروج (logout)
 * - الحصول على معلومات الملف الشخصي (profile)
 * 
 * العلاقات:
 * - User: hasOne FarmerProfile أو ExpertProfile حسب الدور
 * 
 * يستخدم Laravel Sanctum للتوكنات (API tokens)
 */
class AuthController extends ApiController
{
    /**
     * تسجيل مستخدم جديد API
     * 
     * تقوم هذه الدالة بـ:
     * - التحقق من صحة البيانات (الاسم، البريد، كلمة المرور، الدور)
     * - تشفير كلمة المرور
     * - تعيين حالة المستخدم إلى 'pending' (بانتظار إكمال الملف الشخصي)
     * - إنشاء المستخدم في قاعدة البيانات
     * - إنشاء ملف شخصي فارغ حسب الدور (FarmerProfile أو ExpertProfile)
     * - إنشاء توكن للمستخدم (API token)
     * - إرجاع استجابة JSON تحتوي على: التوكن، الاسم، الدور
     * 
     * الأدوار المتاحة: farmer, expert
     * حالات المستخدم: pending (بانتظار إكمال الملف), active (نشط)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'required|in:farmer,expert',
            'specialization' => 'required_if:role,expert',
        ]);

        if($validator->fails()){
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);       
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        // الحالة الافتراضية: pending حتى يكمل الملف الشخصي
        $input['status'] = 'pending';

        $user = User::create($input);
        
        // إنشاء ملف شخصي حسب الدور
        if ($user->role === 'farmer') {
            $user->farmerProfile()->create();
        } elseif ($user->role === 'expert') {
            $user->expertProfile()->create([
                'specialization' => $request->specialization
            ]);
        }

        // إنشاء توكن API
        $success['token'] =  $user->createToken('ReefyApp')->plainTextToken;
        $success['user'] =  $user;

        return $this->successResponse($success, 'User register successfully.');
    }

    /**
     * تسجيل دخول المستخدم API
     * 
     * تقوم هذه الدالة بـ:
     * - محاولة المصادقة باستخدام البريد الإلكتروني وكلمة المرور
     * - في حالة النجاح:
     *   * إنشاء توكن API جديد للمستخدم
     *   * إرجاع: التوكن، الاسم، الدور، رقم المستخدم
     * - في حالة الفشل:
     *   * إرجاع خطأ Unauthorised مع HTTP code 401
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // محاولة تسجيل الدخول
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            
            // إنشاء توكن جديد
            $success['token'] =  $user->createToken('ReefyApp')->plainTextToken; 
            $success['user'] =  $user;
   
            return $this->successResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->errorResponse('Unauthorised.', ['error'=>'Unauthorised'], 401);
        } 
    }

    /**
     * تسجيل خروج المستخدم API
     * 
     * تقوم هذه الدالة بـ:
     * - حذف التوكن الحالي للمستخدم (تسجيل الخروج)
     * - إرجاع استجابة نجاح
     * 
     * ملاحظة: يتم المصادقة عبر التوكن في الـ header
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], 'User logged out successfully.');
    }

    /**
     * الحصول على معلومات المستخدم الحالي
     * 
     * تقوم هذه الدالة بـ:
     * - جلب بيانات المستخدم المصادق عليه (عبر التوكن)
     * - تحميل الملف الشخصي (FarmerProfile أو ExpertProfile)
     * - إرجاع كامل بيانات المستخدم مع ملفه الشخصي
     * 
     * العلاقات: User hasOne FarmerProfile, User hasOne ExpertProfile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request) {
        $user = $request->user();
        // تحميل الملف الشخصي
        $user->load(['farmerProfile', 'expertProfile']);
        return $this->successResponse($user, 'User profile retrieved successfully.');
    }

    /**
     * تحديث توكن FCM للمستخدم
     */
    public function updateFcmToken(Request $request) {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return $this->successResponse([], 'FCM token updated successfully.');
    }
}
