<?php

namespace App\Aggregate\Competition\Domain\ValueObject;

use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Aggregate\Competition\Domain\Exception\CannotBePastDateException;

final class CompetitionStartDateTime extends DateTimeValueObject
{
    public function __construct(\DateTime $date)
    {
        parent::__construct($date);

        $this->assertNotPastDate($date);
    }

    private function assertNotPastDate(\DateTime $date): void
    {
        $now = new \DateTime();
        if ($date < $now) {
            throw new CannotBePastDateException($date);
        }
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}
