<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\v1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return ApiResponse::success([
                    'redirect' => '/dashboard'
                ], 'Email already verified');
            }
            return redirect()->intended('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return ApiResponse::success([
            'status' => 'verification-link-sent',
        ]);
    }
}
