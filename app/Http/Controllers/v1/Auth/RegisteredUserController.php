<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\UserRegistrationRequest;
use App\Http\Responses\v1\ApiResponse;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRegistrationRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->string('password')),
            'is_admin' => false,
        ]);

        $this->handleProfilePicture($user, $request->profile_picture);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    public function handleProfilePicture($user, $profilePictureUrl){
        // Extract filename from the URL
        $filename = basename(parse_url($profilePictureUrl, PHP_URL_PATH));
        $tempFolder = config('file.temporary_folder');

        // Move the file from temporary to permanent storage
        if ($profilePictureUrl && Storage::disk('public')->exists("{$tempFolder}/{$filename}")) {
            $newPath = "profiles/{$user->id}/{$filename}";

            // Move the file to the new location
            Storage::disk('public')->move("{$tempFolder}/{$filename}", $newPath);

            // Update user with the new profile picture path
            $user->profile_picture = $newPath;
            $user->save();
        }
    }
}
