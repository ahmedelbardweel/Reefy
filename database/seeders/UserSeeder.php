<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ExpertProfile;
use App\Models\FarmerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'أحمد المسؤول',
            'email' => 'admin@reefy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Experts
        $experts = [
            [
                'name' => 'د. خالد عمر',
                'email' => 'khaled@reefy.com',
                'specialization' => 'مكافحة الآفات الزراعية',
                'qualification' => 'دكتوراه في وقاية النبات',
            ],
            [
                'name' => 'م. سارة سالم',
                'email' => 'sara@reefy.com',
                'specialization' => 'الزراعة المائية والحديثة',
                'qualification' => 'ماجستير في هندسة الري',
            ],
            [
                'name' => 'الخبير حسن محمد',
                'email' => 'hassan@reefy.com',
                'specialization' => 'تحليل التربة وتسميد المحاصيل',
                'qualification' => 'خبير زراعي معتمد - 15 سنة خبرة',
            ],
        ];

        foreach ($experts as $expertData) {
            $user = User::create([
                'name' => $expertData['name'],
                'email' => $expertData['email'],
                'password' => Hash::make('password'),
                'role' => 'expert',
            ]);

            ExpertProfile::create([
                'user_id' => $user->id,
                'specialization' => $expertData['specialization'],
                'qualification' => $expertData['qualification'],
                'is_verified' => true,
            ]);
        }

        // 3. Create Farmers
        $farmers = [
            ['name' => 'يوسف المزارع', 'email' => 'youssef@reefy.com', 'city' => 'غزة'],
            ['name' => 'محمود أبو الخير', 'email' => 'mahmoud@reefy.com', 'city' => 'خان يونس'],
            ['name' => 'علي المزارع', 'email' => 'ali@reefy.com', 'city' => 'رفح'],
            ['name' => 'إبراهيم حسن', 'email' => 'ibrahim@reefy.com', 'city' => 'دير البلح'],
            ['name' => 'سعيد محمد', 'email' => 'saeed@reefy.com', 'city' => 'النصيرات'],
        ];

        foreach ($farmers as $farmerData) {
            $user = User::create([
                'name' => $farmerData['name'],
                'email' => $farmerData['email'],
                'password' => Hash::make('password'),
                'role' => 'farmer',
            ]);

            FarmerProfile::create([
                'user_id' => $user->id,
                'bio' => 'مزارع شغوف بالأرض والزراعة العضوية في ' . $farmerData['city'],
                'city' => $farmerData['city'],
                'experience_years' => rand(3, 20),
                'address' => $farmerData['city'] . ', فلسطين',
            ]);
        }
    }
}
