<?php

namespace App\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Resource;
use App\Entities\Traits\IdPropertyTrait;
use App\Entities\Traits\PropertyValidatorTrait;
use App\Entities\Traits\TimestampPropertyTrait;
use InvalidArgumentException;
use JsonSerializable;

class ResourcePermission implements JsonSerializable
{
    use IdPropertyTrait,
        TimestampPropertyTrait,
        PropertyValidatorTrait;

    private ?Resource $resource;
    private bool $view = FALSE;
    private bool $create = FALSE;
    private bool $update = FALSE;
    private bool $delete = FALSE;

    public function __construct (array $data = NULL)
    {
        if (isset($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }

        if (isset($data['resource'])) {
            $this->setResource(
                $data['resource'] instanceof Resource ? $data['resource'] : new Resource($data['resource'])
            );
        }

        $this->setPermission('view', $data['view'] ?? FALSE);
        $this->setPermission('create', $data['create'] ?? FALSE);
        $this->setPermission('update', $data['update'] ?? FALSE);
        $this->setPermission('delete', $data['delete'] ?? FALSE);

        if (isset($data['timestamp']) && $data['timestamp'] instanceof Timestamp) {
            $this->setTimestamp($data['timestamp']);
        } else {
            $this->setTimestamp(new Timestamp($data));
        }
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getResourceField(string $field)
    {
        return $this->resource->getProperty($field);
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function hasResource(): bool
    {
        return isset($this->resource) ? $this->resource->hasProperty('id') : FALSE;
    }

    public function hasResourceById(int $id): bool
    {
        return $this->getResourceField('id') == $id;
    }

    public function setPermission(string $action, bool $permission)
    {
        switch ($action) {
            case 'view':
                $this->view = $permission;
                break;

            case 'create':
                $this->create = $permission;
                break;

            case 'update':
                $this->update = $permission;
                break;

            case 'delete':
                $this->delete = $permission;
                break;

            default:
                throw new InvalidArgumentException('Ação não encontrada ao modificar permissão');
                break;
        }
    }

    public function hasPermission(string $action): bool
    {
        switch ($action) {
            case 'view':
                return $this->view;
                break;

            case 'create':
                return $this->create;
                break;

            case 'update':
                return $this->update;
                break;

            case 'delete':
                return $this->delete;
                break;

            default:
                throw new InvalidArgumentException('Ação não encontrada ao verificar permissão');
                break;
        }
    }

    public function getModelData(): array
    {
        return [
            'id' => $this->id,
            'view' => $this->view,
            'create' => $this->create,
            'update' => $this->update,
            'delete' => $this->delete,
            'resource_id' => $this->hasResource() ? $this->resource->getId() : NULL,
        ];
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
