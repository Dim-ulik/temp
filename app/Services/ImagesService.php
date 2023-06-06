<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesService {
    public static function saveImage(Request $request, $key)
    {
        if (!$request->hasFile($key)) {
            return null;
        }

        $file_name = time() . '_' . $request->file($key)->getClientOriginalName();
        $request->file($key)->storeAs('uploads', $file_name, 'public');
        return $file_name;
    }

    public static function getImageNameFromUrl($url)
    {
        $urlParts = explode('/', $url);
        return $urlParts[5];
    }

    public static function deletePhoto($name)
    {
        Storage::disk('public')->delete('uploads/' . $name);
    }
}
