<?php

namespace App\UI\Command;

use App\Domain\Candidate;
use App\Tests\Infrastructure\Stubs\CandidateStubBuilder;
use App\Tests\Infrastructure\Stubs\StubBooleanValueObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class GenerateTestFileConsoleCommand extends Command
{

    protected static $defaultName = 'test:generate-file';
    private Filesystem $filesystem;
    private SerializerInterface $serializer;
    private array $candidates;
    private $projectDir;

    public function __construct(Filesystem $filesystem, SerializerInterface $serializer, $projectDir)
    {
        parent::__construct(self::$defaultName);
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->projectDir = $projectDir;
    }

    public function configure()
    {
        parent::configure();

        $this->setDescription('Generate test request file');
        $this->addArgument('numCandidates', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $numCandidates = $input->getArgument('numCandidates');

        for ($i = 0; $i < $numCandidates; $i++) {
            $candidate = CandidateStubBuilder::create()->withMembership(StubBooleanValueObject::random())->build();

            $this->addCandidate($candidate);
        }

        $this->writeCandidateFile($this->candidates);
    }

    private function addCandidate(Candidate $candidate)
    {
        $activities = $candidate->requestedActivities();
        $this->candidates[] = [
            'email' => $candidate->email()->value(),
            'candidate' => $candidate->candidateName()->value(),
            'group' => $candidate->candidateGroup()->value(),
            'desired_activity_count' => $candidate->desiredActivityCount()->value(),
            'is_member' => $candidate->isMember() ? 'yes' : 'no',
            'option1' => $activities->first()->code()->value(),
            'option2' => $activities->offsetExists(1) ? $activities->offsetGet(1)->code()->value() : null,
            'option3' => $activities->offsetExists(2) ? $activities->offsetGet(2)->code()->value() : null,
            'option4' => $activities->offsetExists(3) ? $activities->offsetGet(3)->code()->value() : null,
            'option5' => $activities->offsetExists(4) ? $activities->offsetGet(4)->code()->value() : null,

        ];
    }

    private function writeCandidateFile(array $candidates)
    {
        $fileContents = $this->serializer->encode($this->candidates, 'csv', [CsvEncoder::DELIMITER_KEY => ';', CsvEncoder::ENCLOSURE_KEY => '"']);

        $this->filesystem->dumpFile(
            sprintf('%s/tmp/requests_%s.csv', $this->projectDir, uniqid()),
            $fileContents
        );
    }
}