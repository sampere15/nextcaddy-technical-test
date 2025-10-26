<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
