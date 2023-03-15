<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\RegisterRequest;
use App\Constants\AuthConstants;
use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function index(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, AuthConstants::REGISTER);
    }
}
