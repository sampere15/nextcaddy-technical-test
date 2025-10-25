<?php
namespace App\Aggregate\Competition\Domain\Exception;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Exception\BaseException;
use App\Aggregate\Competition\Domain\Competition;

final class PlayerClubDontMatchCompetitionClubException extends BaseException
{
    public function __construct(Player $player, Competition $competition) 
    {
        parent::__construct("Player with license {$player->federatedCode()->value()} belongs to a different club than the competition.");
    }
}