<?php

namespace App\Tests\Aggregate\Club\Domain\Mother;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Tests\Shared\Domain\Util\FakerProvider;

final class ClubIdMother
{
    public static function create(?string $value = null): ClubId
    {
        $faker = FakerProvider::getFaker();

        return new ClubId($value ?? $faker->uuid());
    }
}
