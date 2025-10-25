<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\BaseValueObject;

abstract class IntValueObject extends BaseValueObject
{
    protected function __construct(protected int $value) 
    {
    }

    public function value(): int
    {
        return $this->value;
    }
}
