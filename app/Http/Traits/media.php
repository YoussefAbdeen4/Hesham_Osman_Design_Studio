<?php

namespace App\Http\Traits;

// استدعاء كلاسات مكتبة الضغط
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait media
{
    public function uploadPhoto($img, $dir): string
    {
        // 1. توليد اسم الصورة الجديد مع امتداد webp
        $imgName = uniqid() . '.webp';
        
        // 2. تحديد المسار الكامل للمجلد داخل الـ public
        $path = public_path("/dist/img/$dir/");

        // 3. (خطوة أمان) التأكد من أن المجلد موجود فعلاً، وإذا لم يكن موجوداً يتم إنشاؤه
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // 4. تهيئة مدير الصور
        $manager = new ImageManager(new Driver());

        // 5. قراءة الصورة المرفوعة في الذاكرة (الرام)
        $image = $manager->read($img);

        // 6. السحر هنا: حفظ الصورة في المسار الجديد، 
        // المكتبة ستفهم تلقائياً من اسم الملف ($imgName) أنك تريد تحويلها لـ WebP
        // وسيتم ضغط الجودة لـ 80% (مما يقلل الحجم جداً بدون التأثير على الشكل)
        $image->save($path . $imgName, quality: 65);

        return $imgName;
    }

    public function deletePhoto($imgPath): bool
    {
        if (file_exists($imgPath)) {
            unlink($imgPath);
            return true;
        }
        return false;
    }
}