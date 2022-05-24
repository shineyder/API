<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUniquePermissionUpdateRequest;
use App\Services\PermissionMultipleHandlerService;
use App\Services\PermissionUniqueHandlerService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserPermissionController extends Controller
{
    public function updateOnePermission(
        UserUniquePermissionUpdateRequest $request,
        PermissionUniqueHandlerService $service
    )
    {
        try {
            return response()->json(
                $service->handle($request->validated()),
                Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => 'Falha ao atualizar a permissão do usuário. ' . $exception->getMessage()
            ], $exception->getCode());
        }
    }

    public function updateMultiplePermission(
        UserUniquePermissionUpdateRequest $request,
        PermissionMultipleHandlerService $service
    )
    {
        try {
            return response()->json(
                $service->handle($request->validated()),
                Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => 'Falha ao atualizar a permissão do usuário. ' . $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
