<?php

namespace App\Aggregate\Stats\Domain\Repository;

use App\Aggregate\Stats\Domain\Stats;

interface StatsRepository
{
    public function retrieve(): Stats;

    public function save(Stats $stats): void;
}