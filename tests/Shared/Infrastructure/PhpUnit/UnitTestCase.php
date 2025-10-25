<?php

namespace App\Tests\Shared\Infrastructure\PhpUnit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Base class for unit tests in the domain layer. It sets up the Faker library for generating test data.
 */
abstract class UnitTestCase extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create('es_ES');
    }
}
