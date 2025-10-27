<?php

namespace App\Tests\Aggregate\Player\Domain\Mother;

use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;

final class PlayerFederationCodeMother
{
    public static function create(?string $value = null): PlayerFederatedCode
    {
        $faker = FakerProvider::getFaker();

        return new PlayerFederatedCode($value ?? $faker->regexify('[0-9]{6}'));
    }
}
