<?php

namespace App\Controller\Player;

use App\Aggregate\Player\Application\ListPlayers\ListPlayers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerListController extends AbstractController
{
    public function __invoke(
        ListPlayers $listPlayers,
    )
    {
        $players = $listPlayers->__invoke();

        return $this->json($players);
    }
}
