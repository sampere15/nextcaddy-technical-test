<?php

namespace App\Tests\Aggregate\Player\Domain\Mother;

use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;

final class PlayerFederationCodeMother
{
    public static function create(?string $value = null): PlayerFederationCode
    {
        $faker = FakerProvider::getFaker();

        return new PlayerFederationCode($value ?? $faker->regexify('[0-9]{6}'));
    }
}
