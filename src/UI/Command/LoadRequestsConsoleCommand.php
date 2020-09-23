<?php

namespace App\UI\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\Command\RequestActivitiesCommandBuilder;
use App\Application\RequestActivitiesCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class LoadRequestsConsoleCommand extends Command
{

    protected static $defaultName = 'app:load-requests';
    /**
     * @var RequestActivitiesCommandHandler
     */
    private $commandHandler;
    private $projectDir;

    private $serializer;

    /**
     * LoadRequestsConsoleCommand constructor.
     */
    public function __construct(RequestActivitiesCommandHandler $commandHandler, SerializerInterface $serializer, $projectDir)
    {
        parent::__construct(self::$defaultName);
        $this->commandHandler = $commandHandler;
        $this->projectDir = $projectDir;
        $this->serializer = $serializer;
    }

    public function configure()
    {
        parent::configure();

        $this->setDescription('Load candidate requests from given file');
        $this->addArgument('filename', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $requests = $this->serializer->decode(
            $this->getRequestsFileContents($input),
            'csv',
            [
                CsvEncoder::DELIMITER_KEY => ';'
            ]
        );

        foreach ($requests as $request) {
            $this->commandHandler->__invoke(
                $this->buildRequestActivitiesCommand($request)
            );
        }
    }

    /**
     * @param InputInterface $input
     * @return false|string
     */
    protected function getRequestsFileContents(InputInterface $input)
    {
        return file_get_contents(
            $this->projectDir . DIRECTORY_SEPARATOR . $input->getArgument('filename')
        );
    }

    protected function buildRequestActivitiesCommand($request): RequestActivitiesCommand
    {
        $builder = (new RequestActivitiesCommandBuilder())
            ->withEmail($request['email'])
            ->withCandidateName($request['candidate'])
            ->withGroup($request['group'])
            ->withDesiredActivityCount(empty($request['desired_activity_count']) ? null : (int)$request['desired_activity_count']);

        for ($i = 1; $i <= 5; $i++) {
            if (isset($request[sprintf('option%d', $i)])) {
                $builder->withOption($request[sprintf('option%d', $i)]);
            }
        }
        return $builder->build();
    }
}