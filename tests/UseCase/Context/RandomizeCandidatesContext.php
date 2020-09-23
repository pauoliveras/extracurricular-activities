<?php

namespace App\Tests\UseCase\Context;

use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Tests\Infrastructure\Stubs\CandidateStubBuilder;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class RandomizeCandidatesContext extends BaseContext
{
    private $candidateObjectMother;
    private $candidateRepository;

    /**
     * RandomizeCandidatesContext constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->candidateObjectMother = new CandidateStubBuilder();
        $this->candidateRepository = $this->testContainer->get(CandidateRepository::class);
    }

    /**
     * @Given :count candidates have placed a request to any of this activities :activities
     */
    public function candidatesHavePlacedARequestToAnyOfThisActivities(int $count, string $activities)
    {
        for ($i = 1; $i <= $count; $i++) {
            $candidate = $this->candidateObjectMother->withRandomActivities(explode(',', $activities))->build();

            $this->candidateObjectMother->reset();

            $this->saveCandidate($candidate);
        }

        $j = 0;
    }

    private function saveCandidate(Candidate $candidate)
    {
        $this->candidateRepository->save($candidate);
    }

    /**
     * @When /^randomize candidates command is executed$/
     */
    public function randomizeCandidatesCommandIsExecuted()
    {
        $application = new Application($this->kernel);

        $command = $application->find('app:randomize-candidates');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
    }

    /**
     * @Then every candidate has a been assigned a unique number between :min and :max
     */
    public function everyCandidateHasAUniqueNumberAssignedBetweenMinAndMax(int $min, int $max)
    {
        $candidates = $this->candidateRepository->findAll();
        $assignedNumbers = [];
        foreach ($candidates as $candidate) {
            Assert::notNull($candidate->number());
            Assert::greaterThanEq($candidate->number()->value(), $min);
            Assert::lessThanEq($candidate->number()->value(), $max);
            $assignedNumbers[$candidate->number()->value()] = $candidate->number()->value();
        }

        Assert::count($assignedNumbers, $max);
    }

}