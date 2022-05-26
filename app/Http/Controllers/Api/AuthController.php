<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth(guard: 'api')->attempt($credentials)) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        try {
            auth(guard: 'api')->logout();

            return response()->json(['message' => 'logged out'], Response::HTTP_OK);
        } catch (Throwable $exception) {
            if ($exception instanceof JWTException) {
                return response()->json(['message' => 'logged out'], Response::HTTP_OK);
            }

            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    public function userProfile() {
        try {
            $user = auth(guard: 'api')->user();
            return response()->json($this->repository->getEntityById($user['id']), Response::HTTP_OK);
        } catch (Throwable $exception) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    public function error()
    {
        return redirect(Route('home'));
    }
}
