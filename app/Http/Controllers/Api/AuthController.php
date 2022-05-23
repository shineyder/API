<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        /*
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth(guard: 'api')->attempt($credentials)) {
            return redirect(Route('login'));
        }

        $cookie = cookie('jwt', $token, 60);

        return redirect(Route('home'))->withCookie($cookie);
        */

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth(guard: 'api')->attempt($credentials)) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        //$cookie = cookie('jwt', $token, 60);

        return response()->json([
            'token' => $token
        ], Response::HTTP_OK)/* ->withCookie($cookie) */;
    }

    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'logged out'], Response::HTTP_OK);
        } catch (Throwable $exception) {
            if ($exception instanceof JWTException) {
                return response()->json(['message' => 'logged out'], Response::HTTP_OK);
            }

            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    public function error()
    {
        return redirect(Route('home'));
    }
}
