<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPermissionUpdateRequest;
use App\Services\PermissionHandlerService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserPermissionController extends Controller
{
    public function updateUserPermission(
        UserPermissionUpdateRequest $request,
        PermissionHandlerService $service
    )
    {
        try {
            return response()->json([
                'data' => $service->handle($request->validated()),
                'message' => 'Permissões do usuário atualizadas com sucesso'
            ], Response::HTTP_OK);

        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => 'Falha ao atualizar a permissão do usuário. ' . $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
