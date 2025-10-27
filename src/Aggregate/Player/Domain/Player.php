<?php

namespace App\Aggregate\Player\Domain;

use App\Shared\Domain\ValueObject\Gender;
use App\Shared\Domain\Event\AggregateRoot;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Competition\Domain\Competition;
use App\Shared\Domain\ValueObject\BaseValueObject;
use App\Aggregate\Player\Domain\Event\PlayerCreated;
use App\Aggregate\Player\Domain\Event\PlayerUpdated;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerSynced;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;

class Player extends AggregateRoot
{
    /** @var Competition[] $competitions */
    private array $competitions = [];

    private array $updatedFields = [];
    
    private function __construct(
        private readonly PlayerId $id,
        private ClubId $clubId,
        private readonly ?PlayerFederatedCode $federatedCode,
        private readonly PlayerFirstName $name,
        private readonly PlayerSurname $surname,
        private readonly Gender $gender,
        private readonly PlayerBirthdate $birthdate,
        private PlayerActive $active,
        private PlayerSynced $synced,
    ) {
        // Inicializamos el array de campos actualizados
        $this->updatedFields = [];
    }

    public static function create(
        PlayerId $id,
        ClubId $clubId,
        PlayerFederatedCode $federatedCode,
        PlayerFirstName $name,
        PlayerSurname $surname,
        Gender $gender,
        PlayerBirthdate $birthdate,
        PlayerActive $active,
        PlayerSynced $synced = new PlayerSynced(true),
    ): self {
        $player = new self(
            $id,
            $clubId,
            $federatedCode,
            $name,
            $surname,
            $gender,
            $birthdate,
            $active,
            $synced,
        );

        $player->record(new PlayerCreated($player));

        return $player;
    }

    public function id(): PlayerId
    {
        return $this->id;
    }

    public function federatedCode(): ?PlayerFederatedCode
    {
        return $this->federatedCode;
    }

    public function clubId(): ClubId
    {
        return $this->clubId;
    }

    public function name(): PlayerFirstName
    {
        return $this->name;
    }

    public function surname(): PlayerSurname
    {
        return $this->surname;
    }

    public function gender(): Gender
    {
        return $this->gender;
    }

    public function birthdate(): PlayerBirthdate
    {
        return $this->birthdate;
    }

    public function active(): PlayerActive
    {
        return $this->active;
    }

    private function updateActiveStatus(PlayerActive $active): void
    {
        // Si no ha cambiado, no hacemos nada
        if ($this->active->value() === $active->value()) {
            return;
        }

        $this->updatedFields['active'] = [
            'old' => $this->active->value(),
            'new' => $active->value(),
        ];

        $this->active = $active;
    }

    private function updateClub(ClubId $clubId): void
    {
        // Si no ha cambiado, no hacemos nada
        if ($this->clubId->value() === $clubId->value()) {
            return;
        }

        $this->updatedFields['clubId'] = [
            'old' => $this->clubId->value(),
            'new' => $clubId->value(),
        ];

        $this->clubId = $clubId;
    }

    public function update(BaseValueObject ...$valueObjects): void
    {
        foreach ($valueObjects as $valueObject) {
            match (true) {
                $valueObject instanceof PlayerActive => $this->updateActiveStatus($valueObject),
                $valueObject instanceof ClubId => $this->updateClub($valueObject),
                default => null,
            };
        }

        if (!empty($this->updatedFields)) {
            $this->record(new PlayerUpdated($this, $this->updatedFields));

            // Reseteamos el array de campos actualizados
            $this->updatedFields = [];
        }
    }

    public function markAsUnsynced(): void
    {
        $this->synced = new PlayerSynced(false);
    }

    public function isSynced(): PlayerSynced
    {
        return $this->synced;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->value(),
            'federated_code' => $this->federatedCode?->value(),
            'club_id' => $this->clubId->value(),
            'name' => $this->name->value(),
            'surname' => $this->surname->value(),
            'gender' => $this->gender->value(),
            'birthdate' => $this->birthdate->value(),
            'active' => $this->active->value(),
            'synced' => $this->synced->value(),
        ];
    }

    /** @return Competition[] */
    public function competitions(): array
    {
        return $this->competitions;
    }
}
