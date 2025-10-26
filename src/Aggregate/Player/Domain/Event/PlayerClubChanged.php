<?php

namespace App\Aggregate\Player\Domain\Event;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Shared\Domain\Event\DomainEvent;

class PlayerClubChanged extends DomainEvent
{
    public function __construct(
        private readonly PlayerId $playerId,
        private readonly ClubId $oldClubId,
        private readonly ClubId $newClubId,
    ) {
        parent::__construct(
            $playerId->value(),
            $this->toPrimitives(),
        );
    }

    public static function eventName(): string
    {
        return 'nextcaddy.technical_test.1.event.player.club_changed';
    }

    public function toPrimitives(): array
    {
        return [
            'old_club_id' => $this->oldClubId->value(),
            'new_club_id' => $this->newClubId->value(),
        ];
    }
}
