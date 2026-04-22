<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\ExpertProfile;
use App\Models\Crop;
use App\Models\Consultation;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfessionalPresentationSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123456');

        // 1. Create Admin
        User::create([
            'name' => 'م. أحمد عبدالله',
            'email' => 'admin@reefy.com',
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Create Farmer
        $farmer = User::create([
            'name' => 'الحاج محمد العامودي',
            'email' => 'farmer@reefy.com',
            'password' => $password,
            'role' => 'farmer',
            'status' => 'active',
        ]);

        FarmerProfile::create([
            'user_id' => $farmer->id,
            'bio' => 'مزارع في منطقة أريحا والأغوار، خبرة تزيد عن 20 عاماً في زراعة الحمضيات والخضروات المحمية.',
            'experience_years' => 20,
            'address' => 'شارع قصر هشام، أريحا',
            'city' => 'أريحا',
            'country' => 'فلسطين',
            'farm_size' => 15.5,
            'phone' => '0599123456',
        ]);

        // 3. Create Expert
        $expert = User::create([
            'name' => 'د. ياسر الخطيب',
            'email' => 'expert@reefy.com',
            'password' => $password,
            'role' => 'expert',
            'status' => 'active',
        ]);

        ExpertProfile::create([
            'user_id' => $expert->id,
            'specialization' => 'مكافحة الآفات والري الحديث',
            'qualification' => 'دكتوراه في الهندسة الزراعية - جامعة القاهرة',
            'license_number' => 'EXP-2024-089',
            'is_verified' => true,
        ]);

        // 4. Create Crops for Farmer
        Crop::create([
            'user_id' => $farmer->id,
            'name' => 'بيارة الليمون البلدي',
            'type' => 'حمضيات',
            'area' => 5,
            'soil_type' => 'طينية خفيفة',
            'irrigation_method' => 'التنقيط الآلي',
            'planting_date' => now()->subYears(3),
            'status' => 'growing',
            'growth_percentage' => 75,
            'health_status' => 'good',
            'description' => 'أشجار ليمون مثمرة، يتم ريها بنظام التنقيط الحديث ومتابعتها دورياً.',
            'image_path' => 'https://images.unsplash.com/photo-1590682680695-43b964a3ae17?q=80&w=1000',
        ]);

        Crop::create([
            'user_id' => $farmer->id,
            'name' => 'مشاتل الفراولة المعلقة',
            'type' => 'فواكه',
            'area' => 2,
            'soil_type' => 'وسط مائي (هيدروبونيك)',
            'irrigation_method' => 'الري المغلق',
            'planting_date' => now()->subMonths(4),
            'expected_harvest_date' => now()->addDays(10),
            'status' => 'harvesting',
            'growth_percentage' => 95,
            'health_status' => 'good',
            'description' => 'زراعة فراولة بنظام التعليق الحديث لضمان نظافة المحصول وجودة الإنتاج.',
            'image_path' => 'https://images.unsplash.com/photo-1464960350423-95c65503e047?q=80&w=1000',
        ]);

        Crop::create([
            'user_id' => $farmer->id,
            'name' => 'حقل الذرة الصفراء',
            'type' => 'حبوب',
            'area' => 8.5,
            'soil_type' => 'رملية محسنة',
            'irrigation_method' => 'الرش المحوري',
            'planting_date' => now()->subDays(20),
            'status' => 'growing',
            'growth_percentage' => 15,
            'health_status' => 'pest',
            'growth_stage' => 'نبتة صغيرة',
            'description' => 'تم البدء في زراعة الذرة قبل 20 يوماً، نلاحظ وجود بعض الحشرات القشرية.',
            'image_path' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?q=80&w=1000',
        ]);

        // 5. Create Consultations
        Consultation::create([
            'user_id' => $farmer->id,
            'expert_id' => $expert->id,
            'subject' => 'علاج حشرة الذبابة البيضاء',
            'question' => 'السلام عليكم دكتور، نلاحظ انتشار كثيف للذبابة البيضاء في محصول البندورة، ما هو أفضل مبيد عضوي يمكن استخدامه؟',
            'response' => 'وعليكم السلام يا حاج محمد. أنصحك باستخدام زيت النيم (Neem Oil) بنسبة 5 مل لكل لتر ماء، والرش في الصباح الباكر أو عند الغروب لتجنب احتراق الأوراق.',
            'status' => 'answered',
            'category' => 'الآفات الزراعية',
        ]);

        Consultation::create([
            'user_id' => $farmer->id,
            'expert_id' => $expert->id,
            'subject' => 'توقيت تسميد الزيتون',
            'question' => 'متى هو الوقت الأمثل لإضافة السماد المركب لأشجار الزيتون في منطقة أريحا؟',
            'response' => 'يفضل إضافة السماد في نهاية شهر ديسمبر أو بداية يناير، قبل بدء موسم النمو الجديد، لضمان امتصاص الجذور للعناصر الغذائية مع مياه الأمطار.',
            'status' => 'answered',
            'category' => 'التسميد والتربة',
        ]);

        Consultation::create([
            'user_id' => $farmer->id,
            'expert_id' => null,
            'subject' => 'ملوحة التربة في منطقة الأغوار',
            'question' => 'نواجه مشكلة في ارتفاع نسبة الملوحة في التربة هذا الموسم، هل هناك معالجات سريعة قبل موسم الزراعة القادم؟',
            'status' => 'pending',
            'category' => 'التربة والري',
        ]);

        // 6. Community Posts
        Post::create([
            'user_id' => $expert->id,
            'type' => 'tip',
            'content' => "تقنيات الري الحديثة في المناطق الجافة\n\nيعتبر الري بالتنقيط تحت السطحي من أفضل الحلول لتقليل فاقد المياه بنسبة تصل إلى 40% مقارنة بالري التقليدي...",
        ]);

        Post::create([
            'user_id' => $farmer->id,
            'type' => 'post',
            'content' => "تجربتي مع زراعة الفراولة المعلقة\n\nاليوم أشارككم نتائج نظام الزراعة المعلقة، الإنتاج أوفر والشغل أريح بكثير والمحصول نظيف جداً.",
        ]);
    }
}
