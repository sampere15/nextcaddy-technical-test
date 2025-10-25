<?php

namespace App\Tests\Shared\Domain;

use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;

/**
 * Abstract test class for those Value Objects that are abstract classes.
 */
abstract class AbstractClassValueObjectTest extends UnitTestCase
{
    protected string $vo;

    abstract protected function getValueObjectClass(): string;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vo = $this->getValueObjectClass();
    }
}