<?php

namespace App\Aggregate\Stats\Application;

use App\Aggregate\Stats\Domain\Repository\StatsRepository;

class IncreaseNumberCompetitorRegistered
{
    public function __construct(private readonly StatsRepository $statsRepository) {}

    public function __invoke(int $increment): void
    {
        $stats = $this->statsRepository->retrieve();
        $stats->increaseNumberCompetitorRegistered($increment);
        $this->statsRepository->save($stats);
    }
}
