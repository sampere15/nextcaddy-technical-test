<?php

namespace App\Aggregate\Competition\Domain\Exception;

use App\Shared\Domain\Exception\BaseException;

class CannotBePastDateException extends BaseException
{
    public function __construct(\DateTime $date)
    {
        parent::__construct("The date {$date->format('Y-m-d H:i:s')} cannot be in the past.");
    }
}
