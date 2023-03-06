<?php

namespace App\Http\Controllers\API;

use App\Constants\AuthConstants;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, AuthConstants::REGISTER);
    }

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
    public function details(): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            return $this->sendResponse($user->toArray(), '');
        }

        return $this->sendError(AuthConstants::UNAUTHORIZED);
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
}
