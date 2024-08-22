<?php

use App\Http\Controllers\v1\Admin\BlogController;
use App\Http\Controllers\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\v1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\v1\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\v1\Auth\NewPasswordController;
use App\Http\Controllers\v1\Auth\PasswordResetLinkController;
use App\Http\Controllers\v1\Auth\RegisteredUserController;
use App\Http\Controllers\v1\Auth\VerifyEmailController;
use App\Http\Controllers\v1\ImageController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function (Request  $request) {
    $connection = DB::connection('mongodb');
    $msg = 'MongoDB is accessible!';
    try {
        $connection->command(['ping' => 1]);
    } catch (\Exception  $e) {
        $msg = 'MongoDB is not accessible. Error: ' . $e->getMessage();
    }
    return ['msg' => $msg];
});

Route::middleware('guest')->group(function(){
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('/reset-password', [NewPasswordController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1']);
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['throttle:6,1']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::put('/user/profile-picture/update', [UserController::class, 'updateProfilePicture']);
    Route::post('upload/image', [ImageController::class, 'store']);
    Route::delete('delete/image', [ImageController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function(){
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blog/{blog}', [BlogController::class, 'show']);
    Route::post('/blog', [BlogController::class, 'store']);
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy']);
});
