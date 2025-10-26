<?php

namespace App\Aggregate\Player\Domain\DTO;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;

class PlayerUpdaterDTO
{
    public function __construct(
        public readonly ?ClubCode $clubCode = null,
        public readonly ?PlayerActive $active = null,
    ) {}
}
