<?php

namespace App\Aggregate\Player\Domain\Event;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Event\DomainEvent;

final class PlayerCreated extends DomainEvent
{
    public function __construct(
        private readonly Player $player,
    ) {
        parent::__construct(
            $player->id()->value(),
            $this->toPrimitives(),
        );
    }

    public static function eventName(): string
    {
        return 'nextcaddy.technical_test.1.event.player.created';
    }

    public function toPrimitives(): array
    {
        return $this->player->jsonSerialize();
    }
}
