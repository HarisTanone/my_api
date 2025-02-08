<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class BaseController extends Controller
{
    public function successHandler($message, $data): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, 200);
    }
    public function successPageHandler($message, $data): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data['data'] ?? []
        ];

        if (isset($data['meta'])) {
            $response['pagination'] = [
                'total' => $data['meta']['total'],
                'count' => $data['meta']['to'],
                'per_page' => $data['meta']['per_page'],
                'current_page' => $data['meta']['current_page'],
                'total_pages' => $data['meta']['last_page'],
                'first_page_url' => $data['links']['first'],
                'last_page_url' => $data['links']['last'],
                'next_page_url' => $data['links']['next'],
                'prev_page_url' => $data['links']['prev'],
            ];
        }

        return response()->json($response, 200);
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
