<?php

namespace App\Tests\Aggregate\Competition\Domain\Mother;

use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;

class CompetitionNameMother
{
    public static function create(?string $value = null): CompetitionName
    {
        $faker = FakerProvider::getFaker();

        return new CompetitionName($value ?? $faker->word());
    }
}
