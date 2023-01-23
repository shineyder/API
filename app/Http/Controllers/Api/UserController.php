<?php

namespace App\Http\Controllers\Api;

use App\Entities\User as UserEntity;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @group User management
 *
 * Handles work with user, that includes display a list of all users, info about a specific user, create, update or delete actions.
 */
class UserController extends Controller
{
    public function __construct(private UserRepository $repository)
    {
    }

    /**
     * Display list of all users.
     *
     * This endpoint allows you to see a list of all users.
     * @authenticated
     */
    public function index()
    {
        try {
            return response()->json($this->repository->list(), Response::HTTP_OK);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao buscar a lista de usuários'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display info of a specific user.
     *
     * This endpoint allows you to see info about a specific user.
     *
     * @urlParam id integer required The ID of the user. Example: 1
     * @authenticated
     */
    public function info($id)
    {
        try {
            return response()->json($this->repository->getEntityById($id), Response::HTTP_OK);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao buscar as informações do usuário'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Send a register request.
     *
     * This endpoint allows you to try register in the system.
     * Unauthenticated users can't do anything, except try login or register.
     * If register request runs successfully, user will be automatically log in.
     *
     * @bodyParam name      string required The name of the user. Example: example
     * @bodyParam email     string required The email of the user. Example: exampleuser@example.com
     * @bodyParam password  string required The email of the user. Example: pa$$word
     */
    public function create(UserCreateRequest $request)
    {
        try {
            $user = new UserEntity([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = $this->repository->create($user);

            $loginRequest = Request::create('api/auth/login', 'POST', ['email' => $request->email, 'password' => $request->password]);

            return app()->handle($loginRequest)->getContent();

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao criar usuário'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Send a update request.
     *
     * This endpoint allows you to update infos of a specific user.
     *
     * @urlParam id integer required The ID of the user. Example: 1
     *
     * @bodyParam name string required The name of the  user. Example: example
     * @bodyParam email string required The email of the  user. Example: exampleuser@example.com
     * @bodyParam password string required The email of the  user. Example: pa$$word
     *
     * @authenticated
     */
    public function update($id, UserUpdateRequest $request)
    {
        try {
            $user = new UserEntity([
                'id' => $id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = $this->repository->update($user);

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'data' => $user->getModelData()
            ], Response::HTTP_OK);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao atualizar usuário'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Send a delete request.
     *
     * This endpoint allows you to delete a specific user.
     *
     * @urlParam id integer required The ID of the user. Example: 1
     * @authenticated
     */
    public function delete($id)
    {
        try {
            $deleted = $this->repository->delete($id);

            return response()->json([
                'message' => 'Usuário apagado com sucesso',
                'data' => [
                    'deleted' => $deleted
                ]
            ], Response::HTTP_OK);

        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => 'Falha ao deletar usuário'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
