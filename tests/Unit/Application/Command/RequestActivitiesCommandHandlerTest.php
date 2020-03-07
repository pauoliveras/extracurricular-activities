<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\RequestActivitiesCommandHandler;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $candidateRepository;
    private $requestActivitiesCommandHandler;

    protected function setUp(): void
    {
        $this->candidateRepository = new InMemoryCandidateRepository();
        $this->requestActivitiesCommandHandler = new RequestActivitiesCommandHandler($this->candidateRepository);
    }

    public function test_candidate_request_is_created_with_provided_data()
    {
        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            ['activity_1', 'activity_2', 'activity_3']
        );

        $this->requestActivitiesCommandHandler->__invoke($command);

        $savedCandidate = $this->candidateRepository->findByEmail(Email::fromString('candidate@email.com'));
        $this->assertNotNull($savedCandidate);
    }

}

class InMemoryCandidateRepository implements CandidateRepository
{

    private $candidate;

    public function save(Candidate $candidate)
    {
        $this->candidate[(string)$candidate->email()] = $candidate;
    }

    public function findByEmail(Email $email): ?Candidate
    {
        return $this->candidate[(string)$email];
    }

    public function nextId(): Id
    {
        return Id::next();
    }
}