<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class HttpResponse
{
    /**
     * Return a standardized API response
     *
     * @param bool $success Whether the request was successful
     * @param string $message Response message
     * @param mixed $data Response data (optional)
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public static function response(
        bool $success = true,
        string $message = '',
        mixed $data = null,
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a success response
     *
     * @param string $message Success message
     * @param mixed $data Response data (optional)
     * @param int $statusCode HTTP status code (default 200)
     * @return JsonResponse
     */
    public static function success(
        string $message = 'Success',
        mixed $data = null,
        int $statusCode = 200
    ): JsonResponse {
        return self::response(true, $message, $data, $statusCode);
    }

    /**
     * Return an error response
     *
     * @param string $message Error message
     * @param mixed $data Error details (optional)
     * @param int $statusCode HTTP status code (default 400)
     * @return JsonResponse
     */
    public static function error(
        string $message = 'Error',
        mixed $data = null,
        int $statusCode = 400
    ): JsonResponse {
        return self::response(false, $message, $data, $statusCode);
    }

    /**
     * Return a 404 not found response
     *
     * @param string $message Not found message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    /**
     * Return a 401 unauthorized response
     *
     * @param string $message Unauthorized message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, 401);
    }

    /**
     * Return a 403 forbidden response
     *
     * @param string $message Forbidden message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, 403);
    }

    /**
     * Return a 422 validation error response
     *
     * @param string $message Validation error message
     * @param mixed $data Validation errors
     * @return JsonResponse
     */
    public static function validationError(string $message = 'Validation failed', mixed $data = null): JsonResponse
    {
        return self::error($message, $data, 422);
    }
}
