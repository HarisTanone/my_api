<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    public function successHandler($message, $data): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function errorHandler($message, $error = []): JsonResponse
    {
        $data = [
            'status' => false,
            'message' => $message
        ];

        if (!empty($error)) {
            $data['error'] = $error;
        }

        return response()->json($data, 401);
    }
}
