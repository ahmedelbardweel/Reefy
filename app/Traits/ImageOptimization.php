<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageOptimization
{
    /**
     * تحسين الصور: تصغير الحجم وضغط الجودة لتقليل الضغط على السيرفر
     * 
     * @param \Illuminate\Http\UploadedFile $file الملف المرفوع
     * @param string $folder المجلد المستهدف
     * @param int $maxWidth العرض الأقصى بالبكسل
     * @param int $quality جودة الضغط (0-100)
     * @return string|bool مسار الصورة المخزنة أو false في حال الفشل
     */
    public function optimizeAndStore($file, $folder = 'uploads', $maxWidth = 1080, $quality = 70)
    {
        try {
            // معلومات الصورة
            list($width, $height, $type) = getimagesize($file->getRealPath());
            
            // إنشاء كائن الصورة بناءً على النوع
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $src = imagecreatefromjpeg($file->getRealPath());
                    break;
                case IMAGETYPE_PNG:
                    $src = imagecreatefrompng($file->getRealPath());
                    // الحفاظ على الشفافية في PNG
                    imagealphablending($src, true);
                    imagesavealpha($src, true);
                    break;
                case IMAGETYPE_GIF:
                    $src = imagecreatefromgif($file->getRealPath());
                    break;
                default:
                    // إذا لم يكن نوعاً مدعوماً، قم بحفظه كما هو
                    return $file->store($folder, 'public');
            }

            // حساب الأبعاد الجديدة (الحفاظ على النسبة والتناسب)
            $newWidth = $width;
            $newHeight = $height;

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * ($maxWidth / $width));
            }

            // إنشاء الصورة الجديدة بالأبعاد المطلوبة
            $dst = imagecreatetruecolor($newWidth, $newHeight);

            // معالجة الشفافية للصورة الجديدة
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // عملية النسخ وتغيير الحجم (Resampling)
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // حفظ الملف مؤقتاً
            $tempPath = tempnam(sys_get_temp_dir(), 'opt_');
            
            // تصدير الصورة بناءً على النوع الأصلي
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($dst, $tempPath, $quality);
                    break;
                case IMAGETYPE_PNG:
                    // تحويل الجودة (0-100) إلى (0-9) لـ PNG
                    $pngQuality = round((100 - $quality) / 10);
                    imagepng($dst, $tempPath, $pngQuality);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($dst, $tempPath);
                    break;
            }

            // توليد اسم ملف فريد وحفظه في التخزين
            $fileName = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = $folder . '/' . $fileName;
            
            Storage::disk('public')->put($path, fopen($tempPath, 'r+'));

            // تنظيف الذاكرة
            imagedestroy($src);
            imagedestroy($dst);
            @unlink($tempPath);

            return $path;

        } catch (\Exception $e) {
            // في حال حدوث أي خطأ، قم بحفظ الملف الأصلي كإجراء احتياطي
            return $file->store($folder, 'public');
        }
    }
}
