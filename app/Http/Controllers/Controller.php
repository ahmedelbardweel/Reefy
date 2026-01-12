<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * الكونترولر الأساسي - Base Controller
 * 
 * هذا هو الكلاس الأب لجميع الكونترولرز في التطبيق (ما عدا الـ API Controllers التي ترث من ApiController)
 * يوفر ميزات أساسية مثل:
 * - AuthorizesRequests: للتحقق من الصلاحيات (Authorization policies)
 * - ValidatesRequests: للتحقق من صحة البيانات (Validation)
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
