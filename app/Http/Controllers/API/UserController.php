<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ])) {
            $user = Auth::user();

            $response = ['token' => $user->createToken('MyApp')->accessToken];

            return new JsonResponse(['success' => $response], 200);
        }

        return new JsonResponse(['error' => 'Unauthorised'], 400);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $response['token'] = $user->createToken('MyApp')->accessToken;
        $response['name'] = $user->name;

        return new JsonResponse(['success' => $response], 201);
    }

    /**
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(['success' => $user], 200);
    }
}
