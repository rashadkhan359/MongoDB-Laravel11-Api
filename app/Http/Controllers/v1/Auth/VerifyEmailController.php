<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\v1\ApiResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse|RedirectResponse
    {
        $dashboardUrl = config('app.frontend_url') . '/dashboard';

        if ($request->user()->hasVerifiedEmail()) {
            return $this->handleResponse($request, $dashboardUrl, 'Email already verified', true);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->handleResponse($request, $dashboardUrl, 'Email verified successfully', true);
    }

    /**
     * Handle the response based on the request type.
     */
    private function handleResponse($request, $redirectUrl, $message, $verified): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return ApiResponse::success([
                'message' => $message,
                'verified' => $verified,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->intended($redirectUrl . '?verified=' . ($verified ? '1' : '0'));
    }
}
