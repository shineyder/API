<?php

namespace App\Repositories;

use App\Entities\Resource as ResourceEntity;
use App\Models\Resource as ResourceModel;
use InvalidArgumentException;

class ResourceRepository
{
    public function __construct(private ResourceModel $model)
    {
    }

    public function list()
    {
        return $this->model->get();
    }

    public function getById(int $id): ResourceModel
    {
        $existentResource = $this->model->find($id);

        if (!isset($existentResource)) {
            throw new InvalidArgumentException('Não foi possível encontrar o Resource com este id');
        }

        return $existentResource;
    }

    public function getBySlug(string $slug): ResourceModel
    {
        $existentResource = ResourceModel::where([
            'slug' => $slug
        ])->first();

        if (!isset($existentResource)) {
            throw new InvalidArgumentException('Não foi possível encontrar o Resource com este slug');
        }

        return $existentResource;
    }

    public function create(ResourceEntity $resource): ResourceEntity
    {
        $newResource = $this->model->create($resource->getModelData());
        $resource->fill($newResource->toArray());

        return $resource;
    }

    public function update(ResourceEntity $resource): ResourceEntity
    {
        if (!$resource->hasProperty('id')) {
            throw new InvalidArgumentException('É necessário um id para atualizar o Resource');
        }

        $existentResource = $this->getById($resource->getId());

        $existentResource->fill($resource->getModelData());
        $existentResource->save();

        $resource->fill($existentResource->toArray());
        return $resource;
    }

    public function delete(int $id): bool
    {
        if ($id < 1) {
            throw new InvalidArgumentException('É necessário um id válido para excluir o Resource');
        }

        return $this->model->destroy($id);
    }
}
