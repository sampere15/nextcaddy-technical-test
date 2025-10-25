<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\Exception\IntLessOrEqualToZeroException;

abstract class IntGreaterThanZero extends IntValueObject
{
    public function __construct(protected int $value)
    {
        $this->assertIsGreaterThanZero($value);

        parent::__construct($value);
    }

    private function assertIsGreaterThanZero(int $value): void
    {
        if ($value <= 0) {
            throw new IntLessOrEqualToZeroException();
        }
    }
}
