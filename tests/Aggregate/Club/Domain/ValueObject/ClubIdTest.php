<?php

namespace Aggregate\Club\Domain\ValueObject;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Tests\Shared\Domain\ValueObject\UuidTest;

final class ClubIdTest extends UuidTest
{
    protected function getValueObjectClass(): string
    {
        return ClubId::class;
    }
}
