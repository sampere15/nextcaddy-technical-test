<?php

namespace App\Tests\Aggregate\Competition\Domain\Mother;

use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;

class CompetitionStartDateTimeMother
{
    public static function create(?string $value = null): CompetitionStartDateTime
    {
        return new CompetitionStartDateTime($value ?? new \DateTime('+' . random_int(1, 365) . ' days'));
    }
}
