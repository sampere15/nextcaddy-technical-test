<?php

namespace Aggregate\Club\Domain\ValueObject;

use App\Aggregate\Club\Domain\ValueObject\ClubName;
use App\Tests\Shared\Domain\ValueObject\StringValueObjectTest;

final class ClubNameTest extends StringValueObjectTest
{
    protected function getValueObjectClass(): string
    {
        return ClubName::class;
    }
}
