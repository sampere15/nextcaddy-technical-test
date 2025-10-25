<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\BaseValueObject;

abstract class DateTimeValueObject extends BaseValueObject
{   
    public function __construct(protected \DateTime $value)
    {
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
