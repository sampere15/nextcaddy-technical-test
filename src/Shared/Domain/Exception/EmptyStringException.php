<?php

namespace App\Shared\Domain\Exception;

final class EmptyStringException extends BaseException
{
    public function __construct()
    {
        parent::__construct("The field cannot be an empty string.");
    }
}
