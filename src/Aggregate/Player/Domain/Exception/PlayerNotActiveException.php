<?php

namespace App\Aggregate\Player\Domain\Exception;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Exception\BaseException;

final class PlayerNotActiveException extends BaseException
{
    public function __construct(Player $player)
    {
        parent::__construct("Player with federation code {$player->federatedCode()->value()} is not active.");
    }
}
