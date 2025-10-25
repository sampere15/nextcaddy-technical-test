<?php

namespace App\Shared\Domain\Exception;

class IntLessOrEqualToZeroException extends BaseException
{
    public function __construct(string $message = "Integer value must be greater than zero.")
    {
        parent::__construct($message);
    }
}
