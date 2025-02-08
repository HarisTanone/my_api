<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;

class BaseController extends Controller
{
    public function successHandler($message, $data, $page = false): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];

        if ($data instanceof LengthAwarePaginator && $page) {
            $response['pagination'] = [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'first_page_url' => $data->url(1),
                'last_page_url' => $data->url($data->lastPage()),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ];

            $response['data'] = $data->items();
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
