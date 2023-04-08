<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponses
{
    /**
     * @param [type] $data
     * @param [type] $message
     * @param [type] $code
     * @return JsonResponse
     */
    protected function success($data, $message = null, $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => '',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param [type] $data
     * @param [type] $message
     * @param [type] $code
     * @return JsonResponse
     */
    protected function error($data, $message = null, $code = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => '',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
