<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpFoundation\Response;

class apiProtectedRoute extends BaseMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);

        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'status' => 'Token Inválido'
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'status' => 'Token expirou'
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'status' => 'Token de Autorização não encontrado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}

