<?php

namespace App\Tests\Aggregate\Player\Domain\Mother;

use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Shared\Infrastructure\Uuid\UuidProvider;

class PlayerIdMother
{
    public static function create(): PlayerId
    {
        return new PlayerId(UuidProvider::generate());
    }
}
