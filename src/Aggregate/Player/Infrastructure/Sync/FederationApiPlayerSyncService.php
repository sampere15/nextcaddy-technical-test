<?php

namespace App\Aggregate\Player\Infrastructure\Sync;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Application\Sync\SyncPlayerDataHandlerDTO;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Shared\Domain\ValueObject\Gender;

final class FederationApiPlayerSyncService implements PlayerSyncContract
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $baseUrl = 'https://fakefederation.nextcaddy.com',
    ) {}

    public function fetchPlayerData(PlayerFederatedCode $code): SyncPlayerDataHandlerDTO
    {
        $response = $this->httpClient->request('GET', $this->baseUrl . '/players/' . $code->value());
        $data = $response->toArray(true);

        return new SyncPlayerDataHandlerDTO(
            federatedCode: new PlayerFederatedCode($data['player']['federatedCode']),
            clubCode: new ClubCode($data['player']['clubCode']),
            surname: new PlayerSurname($data['player']['surname']),
            firstName: new PlayerFirstName($data['player']['firstName']),
            gender: Gender::fromString($data['player']['gender']),
            birthdate: new PlayerBirthdate($data['player']['birthdate']),
            active: new PlayerActive($data['player']['active']),
        );
    }
}
