<?php

namespace App\Http\Controllers\v1;

use App\Http\Responses\v1\ApiResponse;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController
{
    protected $imageService;
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $path = config('file.temporary_folder');
        $filePath = $this->imageService::storeImage($file, $path);
        $fileUrl = Storage::disk('public')->url($filePath);
        // Return the saved file url
        return ApiResponse::success(['url' => $fileUrl]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'url' => 'nullable|string|url',
            'file_path' => 'nullable|string',
        ]);
        if($request->url){
            $this->imageService::deleteFromUrl($request->input('url'));
        }else{
            $filePath = 'storage/' . $request->input('file_path');
            $this->imageService::deleteFromPath($filePath);
        }
        return ApiResponse::noContent();
    }
}
