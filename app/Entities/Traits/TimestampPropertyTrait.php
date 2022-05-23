<?php
namespace App\Entities\Traits;

use App\Entities\Commons\Timestamp;

trait TimestampPropertyTrait
{
    private ?Timestamp $timestamp;

    public function getTimestamp(): ?Timestamp
    {
        return $this->timestamp;
    }

    public function setTimestamp(?Timestamp $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
