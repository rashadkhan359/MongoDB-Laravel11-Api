<?php

namespace App\Http\Responses\v1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    private $data;
    private $statusCode;

    public function __construct($data = null, int $statusCode = Response::HTTP_OK)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function respond(): JsonResponse
    {
        return response()->json($this->data, $this->statusCode);
    }

    public static function success($data = null, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return (new ApiResponse($data, $statusCode))->respond();
    }

    public static function error(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return (new ApiResponse($message, $statusCode))->respond();
    }

    public static function noContent()
    {
        return response()->noContent();
    }

    // You can add additional methods for different types of responses (e.g., created, updated)

    // Getters and setters for message and data can also be added
}
