<?php

namespace App\Aggregate\Player\Application\Exception;

use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Shared\Domain\Exception\BaseException;

class PlayerNotFoundException extends BaseException
{
    public function __construct(PlayerId $playerId)
    {
        parent::__construct("Player with ID {$playerId->value()} not found.");
    }
}
