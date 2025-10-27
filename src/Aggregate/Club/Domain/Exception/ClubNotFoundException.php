<?php

namespace App\Aggregate\Club\Domain\Exception;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Shared\Domain\Exception\NotFoundException;

final class ClubNotFoundException extends NotFoundException
{
    public function __construct(ClubId|ClubCode $data)
    {
        parent::__construct("Club with ID or code {$data->value()} not found.");
    }
}
