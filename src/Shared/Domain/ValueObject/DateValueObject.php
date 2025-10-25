<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\DateException;
use App\Shared\Domain\ValueObject\BaseValueObject;

abstract class DateValueObject extends BaseValueObject
{   
    protected \DateTime $value;

    public function __construct(string $value)
    {
        $this->validateDate($value);
    }

    private function validateDate(string $date): void
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);

        if (!$d || $d->format('Y-m-d') !== $date) {
            throw new DateException("$date is not a valid date in Y-m-d format.");
        }
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
