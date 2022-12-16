<?php

namespace App\Services;

use App\Exceptions\PermissionHandlingException;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class PermissionUniqueHandlerService
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

        $this->handler->handle($user, $data);
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

        return $this;
    }
}
