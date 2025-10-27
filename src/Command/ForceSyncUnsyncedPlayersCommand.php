<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Aggregate\Player\Application\ForceSyncUnsyncedPlayers;

final class ForceSyncUnsyncedPlayersCommand extends Command
{
    protected static $defaultName = 'app:force-sync-unsynced-players';

    protected function configure(): void
    {
        $this
            ->setDescription('Force sync all unsynced players.');
    }

    public function __construct(
        private readonly ForceSyncUnsyncedPlayers $forceSyncUnsyncedPlayers,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->forceSyncUnsyncedPlayers->__invoke();

        return Command::SUCCESS;
    }
}
