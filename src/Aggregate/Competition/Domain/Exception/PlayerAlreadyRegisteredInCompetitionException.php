<?php

namespace App\Aggregate\Competition\Domain\Exception;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Exception\BaseException;

final class PlayerAlreadyRegisteredInCompetitionException extends BaseException
{
    public function __construct(Player $player)
    {
        parent::__construct("Player with federated code {$player->federatedCode()->value()} is already registered in this competition.");
    }
}
