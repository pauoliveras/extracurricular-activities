<?php

namespace App\UI\Command;

use App\Application\Command\GenerateAssignmentsCommand;
use App\Application\GenerateAssignmentsCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAssignmentsConsoleCommand extends Command
{

    protected static $defaultName = 'app:generate-assignments';
    /**
     * @var GenerateAssignmentsCommandHandler
     */
    private $commandHandler;

    /**
     * LoadRequestsConsoleCommand constructor.
     */
    public function __construct(GenerateAssignmentsCommandHandler $commandHandler)
    {
        parent::__construct(self::$defaultName);
        $this->commandHandler = $commandHandler;
    }

    public function configure()
    {
        parent::configure();

        $this->setDescription('Generate candidate assignments based on lucky draw number');
        $this->addArgument('luckyDrawNumber', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandHandler->__invoke(
            new GenerateAssignmentsCommand($input->getArgument('luckyDrawNumber'))
        );
    }
}