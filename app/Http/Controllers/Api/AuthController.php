<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @group Auth management
 *
 * Handles authenticate work including login and logout, token refresh and user profile display
 */
class AuthController extends Controller
{
    public function __construct(private UserRepository $repository)
    {
    }

    /**
     * Send a login request.
     *
     * This endpoint allows you to try login.
     * Unauthenticated users can't do anything, except try login or register.
     *
     * @bodyParam email     string required The email of the user.      Example: example@example.com
     * @bodyParam password  string required The password of the user.   Example: pa$$word
     *
     * @response status=200 scenario="valid credentials" { [ "token": "[a_JWT_Token]" ] }
     * @response status=400 scenario="invalid credentials" { [ ] }
     */
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

    /**
     * Logout.
     *
     * This endpoint try logout the authenticated user.
     * If an error occurs with the token, the authenticated user will be logout anyway.
     *
     * @response status=200 { [ "message": "logged out" ] }
     * @authenticated
     */
    public function logout()
    {
        try {
            auth(guard: 'api')->logout();

            return response()->json(['message' => 'logged out'], Response::HTTP_OK);
        } catch (Throwable $exception) {
            if ($exception instanceof JWTException) {
                return response()->json(['message' => 'logged out'], Response::HTTP_OK);
            }

            //return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get authenticated user data.
     *
     * This endpoint shows infos about authenticated user.
     *
     * @response status=200 {
     *  "id": 1,
     *  "name": "example",
     *  "email": "exampleuser@example.com",
     *  "password": "[some_hash_password]",
     *  "isAdmin": false,
     *  "resourcePermissions": [],
     *  "created_at": "2040-01-01",
     *  "updated_at": "2040-01-01"
     * }
     * @response status=400 { [ ] }
     * @authenticated
     */
    public function userProfile()
    {
        try {
            $user = auth(guard: 'api')->user();
            return response()->json($this->repository->getEntityById($user['id']), Response::HTTP_OK);
        } catch (Throwable $exception) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Refresh token.
     *
     * This endpoint try to refresh JWT Token.
     *
     * @response status=200 { "[a_refresh_JWT_Token]" }
     * @response status=400 { [ ] }
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $token = JWTAuth::refresh($token);
            return response()->json($token, Response::HTTP_OK);
        } catch (Throwable $exception) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

    }
}
