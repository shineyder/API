<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserMultiplePermissionUpdateRequest;
use App\Http\Requests\UserUniquePermissionUpdateRequest;
use App\Services\PermissionMultipleHandlerService;
use App\Services\PermissionUniqueHandlerService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @group Permissions management
 *
 * Update one or more permissions of a user.
 */
class UserPermissionController extends Controller
{
    public function __construct(
        private PermissionUniqueHandlerService $serviceUnique,
        private PermissionMultipleHandlerService $serviceMultiple
    )
    {
    }

    /**
     * Send a update one permission request.
     *
     * This endpoint allows you to update one permission of a specific user.
     *
     * @bodyParam user_id       int     required The id of the user.        Example: 1
     * @bodyParam resource_id   int     required The id of the resource.    Example: 1
     * @bodyParam view          bool    required The view permission.       Example: true
     * @bodyParam create        bool    required The create permission.     Example: true
     * @bodyParam update        bool    required The update permission.     Example: true
     * @bodyParam delete        bool    required The delete permission.     Example: true
     *
     * @authenticated
     */
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

    /**
     * Send a update multiple permission request.
     *
     * This endpoint allows you to update multiple permissions of a specific user.
     *
     * @bodyParam user_id                               int         required The id of the user.            Example: 1
     * @bodyParam resource_permissions                  object[]    required List of permissions details.   Example: [{"resource_id": 1, "view": true, "create": true, "update": true, "delete": true, }]
     * @bodyParam resource_permissions[].resource_id    int         required The id of the resource.        Example: 1
     * @bodyParam resource_permissions[].view           bool        required The view permission.           Example: true
     * @bodyParam resource_permissions[].create         bool        required The create permission.         Example: true
     * @bodyParam resource_permissions[].update         bool        required The update permission.         Example: true
     * @bodyParam resource_permissions[].delete         bool        required The delete permission.         Example: true
     *
     * @authenticated
     */
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
