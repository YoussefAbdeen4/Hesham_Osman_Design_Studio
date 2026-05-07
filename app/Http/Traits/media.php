<?php

namespace App\Http\Traits;

use Intervention\Image\ImageManager;
// استدعاء المحركين معاً فوق
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

trait media
{
    public function uploadPhoto($img, $dir): string
    {
        $imgName = uniqid() . '.webp';
        $path = public_path("/dist/img/$dir/");

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // --- السحر هنا (اختيار المحرك تلقائياً) ---
        // دالة extension_loaded بتفحص هل الإضافة موجودة في الـ PHP ولا لأ
        if (extension_loaded('imagick')) {
            $driver = new ImagickDriver();
        } else {
            $driver = new GdDriver();
        }

        // تمرير المحرك المناسب للمدير
        $manager = new ImageManager($driver);
        $image = $manager->read($img);

        // حفظ الصورة مع تقليل الجودة لـ 60
        $image->save($path . $imgName, quality: 60);

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