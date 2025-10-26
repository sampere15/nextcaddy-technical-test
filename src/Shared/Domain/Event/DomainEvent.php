<?php

namespace App\Shared\Domain\Event;

use App\Shared\Infrastructure\Uuid\UuidProvider;

abstract class DomainEvent
{
    protected readonly string $eventId;
    protected readonly \DateTimeImmutable $occurredOn;

    abstract public static function eventName(): string;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    abstract public function toPrimitives(): array;

    public function __construct(
        protected readonly string $aggregateId,
        protected readonly array $payload = [],
        ?string $eventId = null,
        ?\DateTimeImmutable $occurredOn = null,
    ) {
        $this->eventId = $eventId ?? UuidProvider::generate();
        $this->occurredOn = $occurredOn ?? new \DateTimeImmutable();
    }
}
