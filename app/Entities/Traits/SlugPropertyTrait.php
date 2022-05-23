<?php

namespace App\Entities\Traits;

trait SlugPropertyTrait
{
    private ?string $slug;

    public function getSlug(): ?string
    {
        return $this->slug ?? NULL;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
