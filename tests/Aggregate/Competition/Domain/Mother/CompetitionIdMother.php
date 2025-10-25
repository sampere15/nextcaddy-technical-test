<?php

namespace App\Tests\Aggregate\Competition\Domain\Mother;

use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;

class CompetitionIdMother
{
    public static function create(?string $value = null): CompetitionId
    {
        $faker = FakerProvider::getFaker();

        return new CompetitionId($value ?? $faker->uuid());
    }
}
