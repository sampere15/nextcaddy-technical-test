<?php

namespace App\Aggregate\Stats\Application;

use App\Aggregate\Stats\Domain\Repository\StatsRepository;

class IncreaseNumberCompetitorRegistered
{
    public function __construct(private readonly StatsRepository $statsRepository) {}

    public function __invoke(): void
    {
        $stats = $this->statsRepository->retrieve();
        $stats->increaseNumberCompetitorRegistered();
        $this->statsRepository->save($stats);
    }
}
