<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse|Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => 'Token is Invalid', 'code' => ResponseAlias::HTTP_UNAUTHORIZED], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => 'Token is Expired', 'code' => ResponseAlias::HTTP_UNAUTHORIZED], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->json(['status' => 'Authorization Token not found', 'code' => ResponseAlias::HTTP_UNAUTHORIZED], ResponseAlias::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
