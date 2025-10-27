<?php

namespace App\Aggregate\Player\Application\ListPlayers;

use JsonSerializable;

final class PlayerListItemResponse implements JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $federatedCode,
        public readonly string $name,
        public readonly string $surname,
        public readonly string $gender,
        public readonly string $birthdate,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'federatedCode' => $this->federatedCode,
            'name' => $this->name,
            'surname' => $this->surname,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
        ];
    }
}
