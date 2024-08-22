<?php

namespace App\Service;

use Illuminate\Support\Facades\File;

class ImageService
{
    /**
     * @param File | string
     * @return string
     */
    public static function storeImage($file, $path)
    {
        $ext = $file->getClientOriginalExtension();
        $fileName = 'media_' . uniqid() . '.' . $ext;

        // Save the file to the desired location
        $file->storeAs($path, $fileName, 'public');

        // Return the file path for further use
        return "{$path}/{$fileName}";
    }

    public static function deleteFromUrl($imageUrl)
    {
        $filePath = parse_url($imageUrl, PHP_URL_PATH);
        self::deleteFromPath($filePath);
    }

    /**
     * Remove the image
     * @param string
     * @return null
     */
    public static function deleteFromPath($filePath)
    {
        $fullPath = public_path($filePath);
        if (File::exists($fullPath)) {
            // Delete the file from the server
            File::delete($fullPath);
            // Optionally, you can also remove the file entry from the database if needed
            // Your code here...
        }
    }

    public function move($fileName, $from, $to){

    }
}
