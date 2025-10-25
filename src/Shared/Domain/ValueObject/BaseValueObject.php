<?php

namespace App\Shared\Domain\ValueObject;

use Stringable;

abstract class BaseValueObject implements Stringable
{
    /**
     * Devuelve el valor primitivo del objeto
     *
     * @return mixed
     */
    abstract public function value(): mixed;

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
