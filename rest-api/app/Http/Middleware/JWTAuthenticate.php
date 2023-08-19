<?php

namespace App\Http\Middleware;

use Closure;
// JWT
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
// Symfony
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticate extends BaseMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            // authenticate current user
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $msg = 'Invalid token please Login';
            if ($e instanceof TokenExpiredException) {
                try {
                    $msg = 'Invalid token please Login';
                } catch (TokenBlacklistedException $t) {
                    $msg = 'The token has been blacklisted';
                }
            }

            return response()->json((object) [
                'result' => (object) [
                    'success' => false,
                    'message' => $msg,
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
