<?php

namespace App\Shared\Infrastructure\Event\InMemory;

use App\Shared\Domain\Event\EventBus;
use App\Shared\Domain\Event\DomainEvent;

class InMemoryEventBus implements EventBus
{
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            // No hacemos nada
        }
    }
}
