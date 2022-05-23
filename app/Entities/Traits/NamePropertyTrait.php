<?php

namespace App\Entities\Traits;

trait NamePropertyTrait
{
    private ?string $name = NULL;

    public function getName(): ?string
    {
        return $this->name ?? NULL;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
