<?php

namespace App\Tests\Aggregate\Club\Domain\Mother;

use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Club\Domain\ValueObject\ClubName;

final class ClubNameMother
{
    public static function create(?string $value = null): ClubName
    {
        $faker = FakerProvider::getFaker();

        return new ClubName($value ?? $faker->company());
    }
}
