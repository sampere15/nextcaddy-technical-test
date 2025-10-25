<?php

namespace App\Tests\Aggregate\Competition\Domain\ValueObject;

use App\Shared\Domain\ValueObject\IntGreaterThanZero;
use App\Tests\Shared\Domain\ValueObject\IntGreaterThanZeroTest;

final class CompetitionMaxPlayersTest extends IntGreaterThanZeroTest
{
    protected function getIntGreaterThanZeroClassName(): string
    {
        return IntGreaterThanZero::class;
    }
}
