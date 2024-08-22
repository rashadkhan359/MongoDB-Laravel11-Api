<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Resources\v1\UserResource;
use App\Http\Responses\v1\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return ApiResponse::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            $errorMessages = [];
            foreach ($e->errors() as $field => $errors) {
                $errorMessages = array_merge($errorMessages, $errors);
            }
            $errorString = implode(', ', $errorMessages);
            return ApiResponse::error(
                "Authentication failed: {$errorString}",
                Response::HTTP_UNAUTHORIZED
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                "An error occurred: {$e->getMessage()}",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::noContent();
    }
}
