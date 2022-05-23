<?php

namespace App\Repositories;

use App\Entities\ResourcePermission as ResourcePermissionEntity;
use App\Models\Resource;
use App\Models\UserResourcePermission as ResourcePermissionModel;
use InvalidArgumentException;

class ResourcePermissionRepository
{
    public function __construct(private ResourcePermissionModel $model)
    {
    }

    /* public function list()
    {
        return $this->model->get();
    }

    public function getById(int $id): ResourcePermissionModel
    {
        $existentResourcePermission = $this->model->find($id);

        if (!isset($existentResourcePermission)) {
            throw new InvalidArgumentException('Não foi possível encontrar o Resource Permission com este id');
        }

        return $existentResourcePermission;
    }*/

    public function getByUserIdAndResourceId(int $userId, int $resourceId): ResourcePermissionModel
    {
        $existentResourcePermission = ResourcePermissionModel::where([
            'user_id' => $userId,
            'resource_id' => $resourceId
        ])->get();

        if (!isset($existentResourcePermission[0])) {
            throw new InvalidArgumentException('Não foi possível encontrar a Resource Permission com este id de usuário e id de recurso');
        }

        return $existentResourcePermission[0];
    }

    public function create(int $userId, ResourcePermissionEntity $resourcePermission): ResourcePermissionEntity
    {
        $data = $resourcePermission->getModelData();
        $data['user_id'] = $userId;

        $newResourcePermission = $this->model->create($data);
        $resourcePermission->fill($newResourcePermission->toArray());

        return $resourcePermission;
    }

    public function update(int $userId, ResourcePermissionEntity $resourcePermission): ResourcePermissionEntity
    {
        $resource = $resourcePermission->getResource();
        if (!$resource->hasProperty('id')) {
            throw new InvalidArgumentException('É necessário um id para atualizar a Resource Permission');
        }

        $existentResourcePermission = $this->getByUserIdAndResourceId($userId, $resource->getId());
        
        $existentResourcePermission->fill($resourcePermission->getModelData());
        $existentResourcePermission->save();

        $resourcePermission->fill($existentResourcePermission->toArray());
        return $resourcePermission;
    }

    /* public function delete(int $id): bool
    {
        if ($id < 1) {
            throw new InvalidArgumentException('É necessário um id válido para excluir a Resource Permission');
        }

        return $this->model->destroy($id);
    } */
}
