<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * @param array $result
     * @param string $message
     * @return JsonResponse
     */
    public function sendResponse(array $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(
        string $error,
        array $errorMessages = [],
        int $code = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        
        return response()->json($response, $code);
    }
}
