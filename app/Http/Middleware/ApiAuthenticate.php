<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\BaseController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\AuthConstants;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate extends BaseController
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = auth('sanctum')->user()) {
            Auth::login($user);
            return $next($request);
        }

        return $this->sendError(AuthConstants::UNAUTHORIZED);
    }
}
