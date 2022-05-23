<?php

namespace App\Entities;

use App\Entities\Commons\Timestamp;
use App\Entities\Traits\IdPropertyTrait;
use App\Entities\Traits\NamePropertyTrait;
use App\Entities\Traits\PropertyValidatorTrait;
use App\Entities\Traits\SlugPropertyTrait;
use App\Entities\Traits\TimestampPropertyTrait;
use JsonSerializable;

class Resource implements JsonSerializable
{
    use IdPropertyTrait,
        NamePropertyTrait,
        SlugPropertyTrait,
        TimestampPropertyTrait,
        PropertyValidatorTrait;

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

        if (isset($data['slug'])) {
            $this->setSlug($data['slug']);
        }

        if (isset($data['timestamp']) && $data['timestamp'] instanceof Timestamp) {
            $this->setTimestamp($data['timestamp']);
        } else {
            $this->setTimestamp(new Timestamp($data));
        }
    }

    public function getModelData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
