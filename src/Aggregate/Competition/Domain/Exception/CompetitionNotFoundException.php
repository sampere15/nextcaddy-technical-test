<?php

namespace App\Aggregate\Competition\Domain\Exception;

use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Shared\Domain\Exception\BaseException;

final class CompetitionNotFoundException extends BaseException
{
    public function __construct(CompetitionId $competitionId)
    {
        parent::__construct("The competition with id {$competitionId->value()} was not found.");
    }
}
