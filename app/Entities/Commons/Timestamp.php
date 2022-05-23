<?php

namespace App\Entities\Commons;

use Carbon\Carbon;
use JsonSerializable;

class Timestamp implements JsonSerializable
{
    private ?Carbon $createdAt;
    private ?Carbon $updatedAt;
    private ?Carbon $deletedAt;

    public function __construct (array $data = null)
    {
        if (isset($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data)
    {
        if (isset($data['created_at'])) {
            $this->setCreatedAt($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            $this->setUpdatedAt($data['updated_at']);
        }

        if (isset($data['deleted_at'])) {
            $this->setDeletedAt($data['deleted_at']);
        }
    }

    public function getCreatedAt(): Carbon|null
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $createdAt = Carbon::parse($createdAt);
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): Carbon|null
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        $updatedAt = Carbon::parse($updatedAt);
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): Carbon|null
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(string $deletedAt): self
    {
        $deletedAt = Carbon::parse($deletedAt);
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
