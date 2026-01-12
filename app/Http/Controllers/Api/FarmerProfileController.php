<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * كونترولر ملف المزارع API - Farmer Profile API Controller
 * 
 * يدير تحديث بيانات الملف الشخصي للمزارع (المدينة، حجم المزرعة، إلخ)
 */
class FarmerProfileController extends ApiController
{
    /**
     * تحديث الملف الشخصي للمزارع
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'farmer') {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $profile = $user->farmerProfile;

        if (!$profile) {
            return $this->errorResponse('Profile not found.');
        }

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'farm_size' => 'nullable|numeric',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $profile->update($request->all());

        // يمكن هنا إضافة تحديث لجدول Users أيضاً (الاسم، البريد) إذا لزم الأمر

        return $this->successResponse($profile, 'Profile updated successfully.');
    }
}
