<?php

namespace App\Aggregate\Competition\Domain\Event;

use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Shared\Domain\Event\DomainEvent;

class CompetitionPlayerUnRegistered extends DomainEvent
{
    public static function eventName(): string
    {
        return 'nextcaddy.technical_test.1.event.competition.player_unregistered';
    }

    public function __construct(
        private readonly CompetitionId $competitionId,
        private readonly PlayerId $playerId,
    ) {
        parent::__construct(
            $competitionId->value(),
            $this->toPrimitives(),
        );
    }

    public function toPrimitives(): array
    {
        return [
            'competition_id' => $this->competitionId->value(),
            'player_id' => $this->playerId->value(),
        ];
    }
}
