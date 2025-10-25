<?php

namespace App\Tests\Shared\Domain\Mother;

use App\Shared\Domain\ValueObject\Gender;
use App\Tests\Shared\Domain\Util\FakerProvider;

final class GenderMother
{
    public static function create(?Gender $gender = null): Gender
    {
        return $gender ?? Gender::fromString(FakerProvider::getFaker()->randomElement(['male', 'female']));
    }
}
