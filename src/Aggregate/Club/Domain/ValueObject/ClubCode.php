<?php

namespace App\Aggregate\Club\Domain\ValueObject;

use App\Aggregate\Club\Domain\Exception\ClubCodeFormatException;
use App\Shared\Domain\ValueObject\StringValueObject;

class ClubCode extends StringValueObject
{
    public function __construct(protected string $value)
    {
        parent::__construct($value);

        $this->assertFormat($this->value);
    }

    /**
     * Checks if the Club Code format is valid.
     */
    private function assertFormat(string $value): void
    {
        // Check this format: AM02
        if (!preg_match("/^[A-Z]{2}[0-9]{2}$/", $value)) {
            throw new ClubCodeFormatException($value);
        }
    }
}
