<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\RequestActivitiesCommandHandler;
use App\Domain\ActivityRepository;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\NullActivity;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $candidateRepository;
    private $requestActivitiesCommandHandler;
    private $activityRepository;

    protected function setUp(): void
    {
        $this->candidateRepository = new InMemoryCandidateRepository();
        $this->activityRepository = $this->createMock(ActivityRepository::class);

        $this->requestActivitiesCommandHandler = new RequestActivitiesCommandHandler(
            $this->candidateRepository,
            $this->activityRepository
        );
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

    public function test_candidate_request_must_have_at_least_one_option_selected()
    {
        $this->expectException(InvalidArgumentException::class);

        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            []
        );

        $this->requestActivitiesCommandHandler->__invoke($command);
    }

    public function test_only_existing_activities_can_be_requested()
    {
        $this->activityRepository->method('findByCode')->willReturn(NullActivity::create());

        $this->expectException(InvalidArgumentException::class);

        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            ['non_existing_activity']
        );

        $this->requestActivitiesCommandHandler->__invoke($command);
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