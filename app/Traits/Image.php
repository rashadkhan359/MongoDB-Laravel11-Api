<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait Image
{



    /**
     * Remove the image
     * @param string
     * @return null
     */
    public function destroyImage($filePath)
    {

        $fullPath = public_path($filePath);
        if (File::exists($fullPath)) {
            // Delete the file from the server
            File::delete($fullPath);
            // Optionally, you can also remove the file entry from the database if needed
            // Your code here...
        }
    }
}
