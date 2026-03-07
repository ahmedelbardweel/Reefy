<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\Task;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * كونترولر لوحة تحكم المزارع - Farmer Dashboard Controller
 * 
 * العلاقات:
 * - User: hasMany Crop (المحاصيل)
 * - Crop: hasMany Task (المهام)
 * - User: hasOne FarmerProfile (الملف الشخصي)
 * 
 * هذا الكونترولر يعرض لوحة التحكم الرئيسية للمزارع مع الإحصائيات والرسوم البيانية
 */
class FarmerDashboardController extends Controller
{
    /**
     * خدمة الطقس
     * @var \App\Services\WeatherService
     */
    protected $weatherService;

    /**
     * إنشاء مثيل جديد من الكونترولر
     * 
     * تقوم هذه الدالة بـ:
     * - حقن خدمة الطقس (WeatherService) عبر Dependency Injection
     * 
     * @param \App\Services\WeatherService $weatherService
     */
    public function __construct(\App\Services\WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * عرض لوحة تحكم المزارع
     * 
     * تقوم هذه الدالة بـ:
     * 
     * 1. جلب بيانات الطقس:
     *    - تحديد المدينة من ملف المزارع (افتراضي: Gaza)
     *    - جلب بيانات الطقس الحالية للمدينة
     * 
     * 2. حساب الإحصائيات العامة:
     *    - عدد المحاصيل النشطة
     *    - عدد المهام المعلقة الإجمالي
     * 
     * 3. إحصائيات الأنظمة المتخصصة:
     *    - pendingIrrigation: عدد مهام الري المعلقة
     *    - pendingTreatments: عدد مهام التسميد ومكافحة الآفات المعلقة
     *    - recentHarvestCount: عدد الحصادات في آخر 30 يوم
     * 
     * 4. حساب استهلاك المياه:
     *    - weeklyWater: مجموع المياه المستخدمة هذا الأسبوع (باللتر)
     * 
     * 5. بيانات الرسوم البيانية (آخر 7 أيام):
     *    - weeklyWaterData: كمية المياه المستخدمة يومياً
     *    - weeklyFertData: كمية الأسمدة المستخدمة يومياً
     *    - chartLabels: أسماء الأيام بالعربية
     * 
     * 6. جلب المحاصيل مع المهام المعلقة
     * 
     * 7. جلب نصائح الخبراء (آخر 5 نصائح)
     * 
     * 8. عرض لوحة التحكم مع جميع البيانات
     * 
     * العلاقات المستخدمة:
     * - User -> Crop -> Task
     * - ExpertTip -> User (Expert)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // جلب بيانات الطقس حسب مدينة المزارع
        $city = optional($user->farmerProfile)->city ?? 'Gaza';
        $weatherData = $this->weatherService->getWeather($city);
        
        // === الإحصائيات العامة ===
        
        // عدد المحاصيل النشطة
        $activeCropsCount = $user->crops()->where('status', 'active')->count();
        
        // عدد المهام المعلقة
        $pendingTasksCount = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('status', 'pending')
            ->count();

        // === إحصائيات الأنظمة المتخصصة ===
        
        // عدد مهام الري المعلقة
        $pendingIrrigation = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'water')
            ->where('status', 'pending')
            ->count();
        
        // عدد مهام المعالجة (تسميد + آفات) المعلقة
        $pendingTreatments = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->whereIn('type', ['fertilizer', 'pest'])
            ->where('status', 'pending')
            ->count();

        // عدد الحصادات الأخيرة (آخر 30 يوم)
        $recentHarvestCount = Task::whereIn('crop_id', $user->crops->pluck('id'))
            ->where('type', 'harvest')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // استهلاك المياه الأسبوعي (إجمالي باللتر)
        $cropIds = $user->crops->pluck('id');
        $weeklyWater = Task::whereIn('crop_id', $cropIds)
            ->where('type', 'water')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->startOfWeek(Carbon::SUNDAY))
            ->sum('water_amount');

        // === بيانات الرسوم البيانية (آخر 7 أيام) ===
        
        $weeklyWaterData = [];
        $weeklyFertData = [];
        $chartLabels = [];
        
        // أسماء الأيام بالعربية
        $arabicDays = [
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت'
        ];

        // حساب البيانات اليومية لآخر 7 أيام
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayName = $day->format('l');
            $chartLabels[] = $arabicDays[$dayName] ?? $dayName;
            
            // كمية المياه المستخدمة في هذا اليوم
            $waterSum = Task::whereIn('crop_id', $cropIds)
                ->where('type', 'water')
                ->where('status', 'completed')
                ->whereDate('updated_at', $day)
                ->sum('water_amount');
            
            // كمية الأسمدة المستخدمة في هذا اليوم
            $fertSum = Task::whereIn('crop_id', $cropIds)
                ->where('type', 'fertilizer')
                ->where('status', 'completed')
                ->whereDate('updated_at', $day)
                ->sum('dosage');

            $weeklyWaterData[] = (int)$waterSum;
            $weeklyFertData[] = (int)$fertSum;
        }

        // جلب المحاصيل مع المهام المعلقة فقط
        $crops = $user->crops()->with(['tasks' => function($q) {
            $q->where('status', 'pending')->orderBy('due_date', 'asc');
        }])->latest()->get();

        $weatherData = $this->weatherService->getWeather($city);
        
        // جلب نصائح الخبراء (آخر 5)
        $expertTips = \App\Models\ExpertTip::with('user')->latest()->take(5)->get();

        return view('farmer.dashboard', compact(
            'activeCropsCount', 
            'pendingTasksCount', 
            'pendingIrrigation', 
            'pendingTreatments',
            'recentHarvestCount',
            'weeklyWater',
            'weeklyWaterData',
            'weeklyFertData',
            'chartLabels',
            'crops',
            'weatherData',
            'expertTips'
        ));
    }
}
