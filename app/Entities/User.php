<?php

namespace App\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Traits\IdPropertyTrait;
use App\Entities\Traits\NamePropertyTrait;
use App\Entities\Traits\PropertyValidatorTrait;
use App\Entities\Traits\TimestampPropertyTrait;
use JsonSerializable;

class User implements JsonSerializable
{
    use IdPropertyTrait,
        NamePropertyTrait,
        TimestampPropertyTrait,
        PropertyValidatorTrait;

    private string $email;
    private string $password;
    private bool $isAdmin = FALSE;
    private array $resourcePermissions = [];

    public function __construct (array $data = null)
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

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['email'])) {
            $this->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $this->setPassword($data['password']);
        }

        if (isset($data['is_admin'])) {
            $this->setAdmin($data['is_admin']);
        }

        if (isset($data['resource_permissions']) && is_array($data['resource_permissions'])) {
            foreach ($data['resource_permissions'] as $resourcePermission) {
                $this->addResourcePermissionFromArray($resourcePermission);
            }
        }

        if (isset($data['timestamp']) && $data['timestamp'] instanceof Timestamp) {
            $this->setTimestamp($data['timestamp']);
        } else {
            $this->setTimestamp(new Timestamp($data));
        }
    }

    public function getEmail(): ?string
    {
        return $this->email ?? NULL;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password ?? NULL;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin ?? NULL;
    }

    public function addResourcePermissionFromArray(array $resourcePermission): self
    {
        $this->resourcePermissions[] = new ResourcePermission($resourcePermission);

        return $this;
    }

    public function addResourcePermission(ResourcePermission $resourcePermission): self
    {
        $this->resourcePermissions[] = $resourcePermission;

        return $this;
    }

    public function hasPermission(string $resource, string $action): bool
    {
        foreach ($this->resourcePermissions as $resourcePermission) {
            if ($resourcePermission->getResourceField('slug') == $resource) {
                return $resourcePermission->hasPermission($action);
            }
        }

        return FALSE;
    }

    public function hasResourceById(string $id): bool
    {
        foreach ($this->resourcePermissions as $resourcePermission) {
            if ($resourcePermission->hasResourceById($id)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function getPermissionByResourceId(string $id): ?ResourcePermission
    {
        foreach ($this->resourcePermissions as $resourcePermission) {
            if ($resourcePermission->hasResourceById($id)) {
                return $resourcePermission;
            }
        }

        return NULL;
    }

    public function refreshPermission(ResourcePermission $updatedResourcePermission): self
    {
        foreach ($this->resourcePermissions as $key => $resourcePermission) {
            if ($resourcePermission->hasResourceById(
                $updatedResourcePermission->getResourceField('id')
            )) {
                $this->resourcePermissions[$key] = $updatedResourcePermission;
                return $this;
            }
        }

        return $this;
    }

    public function getModelData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'is_admin' => $this->isAdmin
        ];
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
