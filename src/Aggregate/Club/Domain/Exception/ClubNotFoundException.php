<?php

namespace App\Aggregate\Club\Domain\Exception;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Shared\Domain\Exception\BaseException;
use App\Aggregate\Club\Domain\ValueObject\ClubId;

final class ClubNotFoundException extends BaseException
{
    public function __construct(ClubId|ClubCode $data)
    {
        parent::__construct("Club with ID or code {$data->value()} not found.");
    }
}
