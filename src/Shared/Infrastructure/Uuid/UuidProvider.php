<?php

namespace App\Shared\Infrastructure\Uuid;

use Symfony\Component\Uid\Uuid;

final class UuidProvider
{
    public static function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }

    public static function isValidUuid(string $uuid): bool
    {
        return Uuid::isValid($uuid);
    }
}