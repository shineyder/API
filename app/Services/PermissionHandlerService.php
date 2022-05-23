<?php

namespace App\Services;

use App\Entities\ResourcePermission;
use App\Entities\User;
use App\Exceptions\PermissionHandlingException;
use App\Repositories\ResourcePermissionRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class PermissionHandlerService
{
    public function __construct(
        private ResourcePermissionRepository $resourcePermissionRepository,
        private UserRepository $userRepository,
        private ResourceRepository $resourceRepository
    )
    {
    }

    public function handle(array $data): User
    {
        $this->validate($data);

        $resourcePermission = $this->getResourcePermission($data);

        $user = $this->userRepository->getEntityById($data['user_id']);

        $this->isAdmin();

        if ($user->hasResourceById($data['resource_id'])) {
            return $this->updatePermission($user, $resourcePermission);
        }

        return $this->createPermission($user, $resourcePermission);
    }

    private function validate(array $data): self
    {
        if (empty($data)) {
            throw new PermissionHandlingException('É necessário que os dados sejam válidos', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['resource_id'])) {
            throw new PermissionHandlingException('É necessário que seja informado o recurso', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['user_id'])) {
            throw new PermissionHandlingException('É necessário que seja informado o usuário', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['view'])) {
            throw new PermissionHandlingException('É necessário que seja informado a permissão de visualização', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['create'])) {
            throw new PermissionHandlingException('É necessário que seja informado a permissão de criação', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['update'])) {
            throw new PermissionHandlingException('É necessário que seja informado a permissão de atualização', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['delete'])) {
            throw new PermissionHandlingException('É necessário que seja informado a permissão de remoção', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }

    private function getResourcePermission(array $data): ResourcePermission
    {
        return new ResourcePermission([
            'id' => 1,
            'resource' => [
                'id' => $data['resource_id'],
            ],
            'view' => $data['view'],
            'create' => $data['create'],
            'update' => $data['update'],
            'delete' => $data['delete'],
        ]);
    }

    public function isAdmin()
    {
        if (!auth(guard: 'api')->user()->is_admin) {
            throw new PermissionHandlingException('É necessário permissão de Administrador para realizar essa ação', Response::HTTP_FORBIDDEN);
        }

        return $this;
    }

    private function updatePermission(User $user, ResourcePermission $resourcePermission): User
    {
        $updatedResourcePermission = $this->resourcePermissionRepository->update($user->getId(), $resourcePermission);

        return $user->refreshPermission($updatedResourcePermission);
    }

    private function createPermission(User $user, ResourcePermission $resourcePermission): User
    {
        $createdResourcePermission = $this->resourcePermissionRepository->create($user->getId(), $resourcePermission);

        return $user->addResourcePermission($createdResourcePermission);
    }
}
