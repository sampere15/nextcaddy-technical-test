<?php

namespace App\Shared\Domain\ValueObject;

abstract class BaseValueObject
{
    /**
     * Devuelve el valor primitivo del objeto
     *
     * @return mixed
     */
    abstract public function value(): mixed;
}
