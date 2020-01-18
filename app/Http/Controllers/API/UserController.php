<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\User;
use Symfony\Component\HttpFoundation\Response;

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

            return new JsonResponse(['success' => $response]);
        }

        return new JsonResponse(['error' => trans('api.unauthorised')], Response::HTTP_BAD_REQUEST);
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
            return new JsonResponse(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $response['token'] = $user->createToken('MyApp')->accessToken;
        $response['name'] = $user->name;

        return new JsonResponse(['success' => $response], Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(['success' => $user]);
    }
}
