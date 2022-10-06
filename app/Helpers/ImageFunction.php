<?php

namespace App\Helpers;

class ImageFunction
{
    public static function uploadImage($photo, $path)
    {
        $extension = $photo -> getClientOriginalExtension();
        $number = mt_rand(1000000000, 9999999999); // better than rand()
        $image_name = time() . $number . '.' . $extension;
        $photo->storeAs($path, $image_name);
        return $image_name;
    }

}
