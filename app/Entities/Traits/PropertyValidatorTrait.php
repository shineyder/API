<?php

namespace App\Entities\Traits;

use InvalidArgumentException;

trait PropertyValidatorTrait
{
    public function hasProperty(string $property): bool
    {
        $method = sprintf('get%s', ucfirst($property));
        if (!method_exists($this, $method)) {
            return FALSE;
        }

        return $this->$method() != NULL ? TRUE : FALSE;
    }

    /**
     * @return mixed
     */
    public function getProperty(string $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        if (!method_exists($this, $method)) {
            throw new InvalidArgumentException(
                sprintf('Propriedade %s não encontrada', $property)
            );
        }

        return $this->$method();
    }
}
