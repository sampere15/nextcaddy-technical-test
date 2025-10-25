<?php

namespace App\Aggregate\Player\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class InvalidPlayerFederationCodeException extends BaseException
{
    public function __construct(string $invalidCode)
    {
        parent::__construct("Invalid Player Federation: $invalidCode. It must be a 6-digit numeric string.");
    }
}
