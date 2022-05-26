<?php

namespace App\Services;

use App\Entities\User;
use App\Exceptions\PermissionHandlingException;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class PermissionMultipleHandlerService
{
    public function __construct(
        private UserRepository $userRepository,
        private PermissionHandlerService $handler
    )
    {
    }

    public function handle(array $data): void
    {
        $this->validate($data);

        $user = $this->userRepository->getEntityById($data['user_id']);

        foreach ($data['resource_permissions'] as $resourcePermission) {
            $this->handler->handle($user, $resourcePermission);
        }
    }

    private function validate(array $data): self
    {
        if (empty($data)) {
            throw new PermissionHandlingException('É necessário que os dados sejam válidos', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['user_id'])) {
            throw new PermissionHandlingException('É necessário que seja informado o usuário', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['resource_permissions'])) {
            throw new PermissionHandlingException('É necessário que seja informado as permissões de recurso', Response::HTTP_BAD_REQUEST);
        }

        return $this;
    }
}
