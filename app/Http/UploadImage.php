<?php

class UploadImage
{

    public static function upload_image($photo,$path)
    {
        $extension = $photo -> getClientOriginalExtension();
        $number = mt_rand(1000000000, 9999999999); // better than rand()
        $image_name = time() .$number. '.' . $extension;
        $photo -> storeAs($path,$image_name);
        return response()->json(['name' => $image_name, 'ext' => $extension]);
    }

    public static function upload_video($video,$path)
    {
        $extension = $video -> getClientOriginalExtension();
        $video_name = time() . '.' . $extension;
        $video -> storeAs($path,$video_name);
        return $video_name;
    }

    public static function upload_file($file,$path)
    {
        $extension = $file -> getClientOriginalExtension();
        $file_name = time() . '.' . $extension;
        $file -> storeAs($path,$file_name);
        return $file_name;
    }

}
