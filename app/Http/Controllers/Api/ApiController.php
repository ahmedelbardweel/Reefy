<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * كونترولر API الأساسي - Base API Controller
 * 
 * هذا الكونترولر يحتوي على دوال مساعدة لإرجاع استجابات JSON موحدة
 * جميع API controllers الأخرى ترث من هذا الكونترولر
 */
class ApiController extends Controller
{
    /**
     * إرجاع استجابة نجاح بصيغة JSON
     * 
     * تقوم هذه الدالة بـ:
     * - إنشاء استجابة JSON موحدة للعمليات الناجحة
     * - تحتوي على: success (true), data (البيانات), message (رسالة النجاح)
     * - إرجاع HTTP status code 200
     * 
     * @param mixed $result البيانات المراد إرجاعها
     * @param string $message رسالة النجاح
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * إرجاع استجابة خطأ بصيغة JSON
     * 
     * تقوم هذه الدالة بـ:
     * - إنشاء استجابة JSON موحدة للعمليات الفاشلة
     * - تحتوي على: success (false), message (رسالة الخطأ), data (تفاصيل الأخطاء - اختياري)
     * - إرجاع HTTP status code محدد (افتراضي 404)
     * 
     * @param string $error رسالة الخطأ الرئيسية
     * @param array $errorMessages تفاصيل الأخطاء (مثل أخطاء التحقق)
     * @param int $code رمز حالة HTTP (افتراضي 404)
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
