<?php

namespace App\UI\Command;

use App\Application\Command\RandomizeCandidatesCommand;
use App\Application\RandomizeCandidatesCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RandomizeCandidatesConsoleCommand extends Command
{

    protected static $defaultName = 'app:randomize-candidates';
    private $commandHandler;

    /**
     * LoadRequestsConsoleCommand constructor.
     */
    public function __construct(RandomizeCandidatesCommandHandler $commandHandler)
    {
        parent::__construct(self::$defaultName);
        $this->commandHandler = $commandHandler;
    }

    public function configure()
    {
        parent::configure();

        $this->setDescription('Randomize candidates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandHandler->__invoke(
            new RandomizeCandidatesCommand()
        );
    }
}