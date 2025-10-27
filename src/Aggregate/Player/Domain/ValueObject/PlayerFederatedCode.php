<?php

namespace App\Aggregate\Player\Domain\ValueObject;

use App\Aggregate\Player\Domain\Exception\InvalidPlayerFederationCodeException;
use App\Shared\Domain\ValueObject\StringValueObject;

class PlayerFederatedCode extends StringValueObject
{
    public const TAM = 6;

    public function __construct(string $value)
    {
        parent::__construct($value);

        $this->assertFormat($this->value);
    }

    /**
     * Checks if the Player Federation Code format is valid.
     */
    private function assertFormat(string $value): void
    {
        $tam = self::TAM;

        if (!preg_match("/^[0-9]{{$tam}}$/", $value)) {
            throw new InvalidPlayerFederationCodeException($value);
        }
    }
}
