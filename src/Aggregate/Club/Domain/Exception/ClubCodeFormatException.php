<?php

namespace App\Aggregate\Club\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class ClubCodeFormatException extends BaseException
{
    public function __construct(string $invalidCode)
    {
        parent::__construct("Invalid Club Code: $invalidCode. It must be a 4-character string with the format: AA00.");
    }
}
