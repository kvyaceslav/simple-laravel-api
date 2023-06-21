<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponses
{
    /**
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data, string $message = null, int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => '',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function error(
        $data,
        string $message = null,
        int $code = ResponseAlias::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'status' => '',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
