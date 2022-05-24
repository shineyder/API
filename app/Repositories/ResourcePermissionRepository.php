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
}
