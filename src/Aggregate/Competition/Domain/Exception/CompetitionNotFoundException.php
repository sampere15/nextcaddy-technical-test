<?php

namespace App\Aggregate\Competition\Domain\Exception;

use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Shared\Domain\Exception\NotFoundException;

final class CompetitionNotFoundException extends NotFoundException
{
    public function __construct(CompetitionId $competitionId)
    {
        parent::__construct("The competition with id {$competitionId->value()} was not found.");
    }
}
