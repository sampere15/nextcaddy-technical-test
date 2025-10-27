<?php

namespace App\Aggregate\Player\Infrastructure\Sync;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Application\Sync\SyncPlayerDataHandlerDTO;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Shared\Domain\ValueObject\Gender;

final class FakedPlayerSyncService implements PlayerSyncContract
{
    public function fetchPlayerData(PlayerFederatedCode $code): SyncPlayerDataHandlerDTO
    {
        // Leemos el fichero JSON que se encuentra en .rest-client/player-details/request/GET_player-details.http
        $data = json_decode(file_get_contents('.rest-client/external/fakefederation/player-details/response/GET_player-details_response1.json'), true);

        return new SyncPlayerDataHandlerDTO(
            federatedCode: new PlayerFederatedCode($data['player']['federatedCode']),
            clubCode: new ClubCode($data['player']['clubCode']),
            surname: new PlayerSurname($data['player']['surname']),
            firstName: new PlayerFirstName($data['player']['firstName']),
            gender: Gender::fromShortCode($data['player']['gender']),
            birthdate: new PlayerBirthdate(new \DateTime($data['player']['birthdate'])),
            active: new PlayerActive($data['player']['active']),
        );
    }
}
