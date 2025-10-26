<?php

namespace App\Aggregate\Player\Domain\Event;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Event\DomainEvent;

final class PlayerUpdated extends DomainEvent
{
    /**
     * @param Player $player
     * @param array<string, array<string, string>> $changes
     */
    public function __construct(
        private readonly Player $player,
        private readonly array $changes,
    ) {}

    public static function eventName(): string
    {
        return 'nextcaddy.technical_test.1.event.player.updated';
    }

    public function aggregateId(): string
    {
        return $this->player->id();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toPrimitives(): array
    {
        return [
            'player' => $this->player->jsonSerialize(),
            'changes' => $this->changes,
        ];
    }
}
