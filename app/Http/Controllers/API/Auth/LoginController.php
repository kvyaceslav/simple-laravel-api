<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Constants\AuthConstants;

class LoginController extends Controller
{
    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $success = $user->createToken('MyApp')->plainTextToken;

            return $this->sendResponse(['token' => $success], AuthConstants::LOGIN);
        }

        return $this->sendError(AuthConstants::VALIDATION);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return $this->sendResponse([], AuthConstants::LOGOUT);
    }

    /**
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            return $this->sendResponse($user->toArray(), '');
        }

        return $this->sendError(AuthConstants::UNAUTHORIZED);
    }
}
