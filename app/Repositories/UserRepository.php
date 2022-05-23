<?php

namespace App\Repositories;

use App\Entities\User as UserEntity;
use App\Models\User as UserModel;
use InvalidArgumentException;

class UserRepository
{
    public function __construct(private UserModel $model)
    {
    }

    public function list()
    {
        return $this->model->get();
    }

    public function getEntityById(int $id): UserEntity
    {
        $userModel = $this->model->with(['resourcePermissions', 'resourcePermissions.resource'])->find($id);

        if (!isset($userModel)) {
            throw new InvalidArgumentException('Não foi possível criar a entidade do usuário com este id');
        }

        return new UserEntity($userModel->toArray());
    }

    public function getModelById(int $id): UserModel
    {
        $userModel = $this->model->with(['resourcePermissions', 'resourcePermissions.resource'])->find($id);

        if (!isset($userModel)) {
            throw new InvalidArgumentException('Não foi possível encontrar o usuário com este id');
        }

        return $userModel;
    }

    public function create(UserEntity $user): UserEntity
    {
        $newUser = $this->model->create($user->getModelData());
        $user->fill($newUser->toArray());

        return $user;
    }

    public function update(UserEntity $user): UserEntity
    {
        if (!$user->hasProperty('id')) {
            throw new InvalidArgumentException('É necessário um id para atualizar o usuário');
        }

        $existentUser = $this->getModelById($user->getId());

        $existentUser->fill($user->getModelData());
        $existentUser->save();

        $user->fill($existentUser->toArray());
        return $user;
    }

    public function delete(int $id): bool
    {
        if ($id < 1) {
            throw new InvalidArgumentException('É necessário um id válido para excluir o usuário');
        }

        return $this->model->destroy($id);
    }
}
