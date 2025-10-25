<?php

namespace App\Aggregate\Competition\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

final class MaxPlayersExceededException extends BaseException
{
    public function __construct(int $maxPlayers)
    {
        parent::__construct("Maximum number of players reached for this competition. Limit: $maxPlayers");
    }
}