<?php

namespace App\Entities\Traits;

trait IdPropertyTrait
{
    private ?int $id = NULL;

    public function getId(): ?int
    {
        return $this->id ?? NULL;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
