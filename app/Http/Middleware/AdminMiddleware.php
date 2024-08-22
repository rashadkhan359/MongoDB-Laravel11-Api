<?php

namespace App\Http\Middleware;

use App\Http\Responses\v1\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->is_admin){
            return $next($request);
        }
        return ApiResponse::error("Unauthorised Request.", Response::HTTP_UNAUTHORIZED);
    }
}
