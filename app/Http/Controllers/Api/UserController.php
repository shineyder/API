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

class UserController extends Controller
{
    public function __construct(private UserRepository $repository)
    {
    }

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
            return response()->json([
                'message' => 'Falha ao deletar usuário'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
