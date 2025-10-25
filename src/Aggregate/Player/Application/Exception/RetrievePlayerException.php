<?php

namespace App\Aggregate\Player\Application\Exception;

use App\Shared\Domain\Exception\BaseException;

final class RetrievePlayerException extends BaseException
{
    public function __construct(string $message = "Error retrieving player.")
    {
        parent::__construct($message);
    }
}
