<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserMultiplePermissionUpdateRequest;
use App\Http\Requests\UserUniquePermissionUpdateRequest;
use App\Services\PermissionMultipleHandlerService;
use App\Services\PermissionUniqueHandlerService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserPermissionController extends Controller
{
    public function __construct(
        private PermissionUniqueHandlerService $serviceUnique,
        private PermissionMultipleHandlerService $serviceMultiple
    )
    {
    }

    public function updateOnePermission(UserUniquePermissionUpdateRequest $request)
    {
        try {
            return response()->json(
                $this->serviceUnique->handle($request->validated()),
                Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => 'Falha ao atualizar a permissão do usuário. ' . $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateMultiplePermission(UserMultiplePermissionUpdateRequest $request)
    {
        try {
            return response()->json(
                $this->serviceMultiple->handle($request->validated()),
                Response::HTTP_OK
            );
        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao atualizar a permissão do usuário. ' . $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
