<?php
namespace App\Aggregate\Stats\Domain;

use App\Aggregate\Stats\Domain\ValueObject\CountCompetitions;
use App\Aggregate\Stats\Domain\ValueObject\CountCompetitorsRegistered;

class Stats
{
    private function __construct(
        private CountCompetitions $countCompetitions,
        private CountCompetitorsRegistered $countCompetitorsRegistered,
    )
    {
    }

    public function increaseNumberCompetitorRegistered(): void
    {
        $this->countCompetitorsRegistered++;
    }
}
