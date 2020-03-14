<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\RequestActivitiesCommandHandler;
use App\Domain\Activity;
use App\Domain\ActivityRepository;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\Exception\DuplicateCandidateRequestException;
use App\Domain\NullActivity;
use App\Domain\NullCandidate;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandHandlerTest extends TestCase
{
    /** @var MockObject */
    private $candidateRepository;

    private $requestActivitiesCommandHandler;

    /** @var MockObject | ActivityRepository */
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

        $this->activityRepository->method('findByCode')->willReturnOnConsecutiveCalls(
            new Activity(Id::next(), ActivityCode::fromString('activity_1')),
            new Activity(Id::next(), ActivityCode::fromString('activity_2')),
            new Activity(Id::next(), ActivityCode::fromString('activity_3'))
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
        $this->activityRepository->method('findByCode')
            ->with(ActivityCode::fromString('non_existing_activity'))
            ->willReturn(NullActivity::create());

        $this->expectException(InvalidArgumentException::class);

        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            ['non_existing_activity']
        );

        $this->requestActivitiesCommandHandler->__invoke($command);
    }

    public function test_only_one_request_per_candidate_can_be_placed()
    {
        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            ['activity_1', 'activity_2']
        );

        $this->requestActivitiesCommandHandler->__invoke($command);

        $this->expectException(DuplicateCandidateRequestException::class);

        $command = new RequestActivitiesCommand(
            'candidate@email.com',
            'Candidate name',
            'Candidate group',
            ['activity_3']
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

    public function findByEmail(Email $email): Candidate
    {
        return isset($this->candidate[(string)$email]) ? $this->candidate[(string)$email] : NullCandidate::create();
    }

    public function nextId(): Id
    {
        return Id::next();
    }
}