<?php

namespace App\Tests\Shared\Domain\Util;

use Faker\Factory;
use Faker\Generator;

final class FakerProvider
{
    private static ?Generator $faker = null;

    public static function getFaker()
    {
        if (null === self::$faker) {
            self::$faker = Factory::create('es_ES');
        }

        return self::$faker;
    }
}