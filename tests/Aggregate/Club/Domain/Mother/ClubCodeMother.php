<?php

namespace App\Tests\Aggregate\Club\Domain\Mother;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Tests\Shared\Domain\Util\FakerProvider;

final class ClubCodeMother
{
    public static function create(?string $value = null): ClubCode
    {
        $faker = FakerProvider::getFaker();

        return new ClubCode($value ?? $faker->regexify('[A-Z]{2}[0-9]{2}'));
    }
}
