<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RandomizeCandidatesCommand;
use App\Application\RandomizeCandidatesCommandHandler;
use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Tests\Infrastructure\Stubs\CandidateStubBuilder;
use App\Tests\Infrastructure\Stubs\InMemoryCandidateRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RandomizeCandidatesCommandHandlerTest extends TestCase
{
    /** @var MockObject */
    private $candidateRepository;

    private $randomizeCanidatesCommandHandler;

    public function test_all_candidates_are_persisted()
    {
        $this->givenNCandidates(10);

        $command = new RandomizeCandidatesCommand();

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $this->assertEquals(10, $this->candidateRepository->savedCandidates());
    }

    // random number between 1 and N
    // random number unique
    // every candidate gets one number assigned

    // random number stack
    // is empty at the end
    // implementation details!!!

    private function givenNCandidates(int $rand)
    {
        $candidateStubBuilder = new CandidateStubBuilder();
        for ($i = 1; $i <= $rand; $i++) {
            $this->candidateRepository->save($candidateStubBuilder->build());
            $candidateStubBuilder->reset();
        }

        $this->candidateRepository->reset();
    }

    public function test_every_candidate_gets_a_number()
    {
        $this->givenNCandidates(10);

        $command = new RandomizeCandidatesCommand();

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $processedCandidates = $this->candidateRepository->findAll();

        $this->assertCount(10,
            array_filter(
                $processedCandidates->toArray(),
                function (Candidate $candidate) {
                    return !empty($candidate->number());
                }
            )

        );
    }

    public function test_assigned_numbers_range_from_1_to_number_of_candidates()
    {
        $this->givenNCandidates(10);

        $command = new RandomizeCandidatesCommand();

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $processedCandidates = $this->candidateRepository->findAll();

        $this->assertLessThanOrEqual(10, max(
                $this->assignedNumbersAsArray($processedCandidates)
            )
        );

        $this->assertGreaterThanOrEqual(1, min(
                $this->assignedNumbersAsArray($processedCandidates)
            )
        );
    }

    /**
     * @param CandidateCollection $processedCandidates
     * @return array
     */
    protected function assignedNumbersAsArray(CandidateCollection $processedCandidates): array
    {
        return array_map(
            function (Candidate $candidate) {
                return $candidate->number()->value();
            },
            $processedCandidates->toArray()
        );
    }

    public function test_assigned_number_of_every_candidate_is_unique()
    {
        $this->givenNCandidates(10);

        $command = new RandomizeCandidatesCommand();

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $processedCandidates = $this->candidateRepository->findAll();

        $this->assertCount(10, array_unique(
                $this->assignedNumbersAsArray($processedCandidates)
            )
        );
    }

    public function test_assigned_numbers_are_random()
    {
        $this->givenNCandidates(10);

        $command = new RandomizeCandidatesCommand();

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $firstExecution = $this->assignedNumbersAsArray($this->candidateRepository->findAll());

        $this->randomizeCanidatesCommandHandler->__invoke($command);

        $secondExecution = $this->assignedNumbersAsArray($this->candidateRepository->findAll());

        $this->assertNotEquals(
            $firstExecution,
            $secondExecution
        );
    }

    protected function setUp(): void
    {
        $this->candidateRepository = new InMemoryCandidateRepository();

        $this->randomizeCanidatesCommandHandler = new RandomizeCandidatesCommandHandler(
            $this->candidateRepository
        );
    }

}