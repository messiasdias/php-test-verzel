<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class ApiAuthRoutes extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $request->authUser = \JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(["errors" => [
                    'access_token' => "Token Expired"
                ]], 401);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(["errors" => [
                    'access_token' => "Token Invalid"
                ]], 401);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(["errors" => [
                    'access_token' => "Token Blacklisted"
                ]], 401);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\UserNotDefinedException) {
                return response()->json(["errors" => [
                    'access_token' => "User Not Defined"
                ]], 403);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json(["errors" => [
                    'access_token' => $e->getMessage()
                ]], 403);
            }
        }
        
        return $next($request);
    }
}
