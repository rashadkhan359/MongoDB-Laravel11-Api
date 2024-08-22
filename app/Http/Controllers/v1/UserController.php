<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\v1\UserResource;
use App\Http\Responses\v1\ApiResponse;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController
{
    public function profile(Request $request)
    {
        return ApiResponse::success(new UserResource($request->user()));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'string|max:255',
            'email' => ['string','email','max:255',
                Rule::unique('users')->ignore($user->id, '_id'), //need to tell in mongo id is different
            ],
            'phone' => ['nullable', 'string','max:10',
                Rule::unique('users')->ignore($user->id, '_id'),
            ],
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return ApiResponse::success(new UserResource($user));
    }

    public function updateProfilePicture(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'profile_picture' => 'required|string|url',
        ]);
        $currentPictureUrl = $user->profile_picture;

        $newPictureUrl = $request->profile_picture;

        // Extract filename from the URL
        $filename = basename(parse_url($newPictureUrl, PHP_URL_PATH));
        $tempFolder = config('file.temporary_folder');

        // Move the file from temporary to permanent storage
        if ($newPictureUrl && Storage::disk('public')->exists("{$tempFolder}/{$filename}")) {
            $newPath = "profiles/{$user->id}/{$filename}";

            // Move the file to the new location
            Storage::disk('public')->move("{$tempFolder}/{$filename}", $newPath);

            // Update user with the new profile picture path
            $user->profile_picture = $newPath;
            $user->save();
        }

        ImageService::deleteFromUrl($currentPictureUrl);

        return ApiResponse::noContent();
    }
}
