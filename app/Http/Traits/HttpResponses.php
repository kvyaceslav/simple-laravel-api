<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait HttpResponses
{
    /**
     * @param [type] $data
     * @param [type] $message
     * @param [type] $code
     * @return JsonResponse
     */
    protected function success($data, $message = null, $code = Response::HTTP_OK): JsonResponse
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
    protected function error($data, $message = null, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => '',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
