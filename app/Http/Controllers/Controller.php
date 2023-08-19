<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Set success response
     */
    protected function success(?string $message, mixed $data, $statusCode = 201, ?int $limit = 0, ?int $page = 0): JsonResponse
    {
        $payload = [
            'result' => array_filter([
                'success' => true,
                'message' => $message,
                'limit' => $limit,
                'page' => $page,
                'data' => $data,
            ]),
        ];

        return response()->json($payload, $statusCode);
    }

    /**
     * Set Error response
     */
    protected function error($message = 'Internal error', $statusCode = 500): JsonResponse
    {
        // reorganize array message
        if (!is_scalar($message)) {
            $message = array_map('current', $message);
        }
        return response()->json([
            'result' => [
                'success' => false,
                'message' => $message,
                'data' => null,
            ],
        ], $statusCode);
    }
}
