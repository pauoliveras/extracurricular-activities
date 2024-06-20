<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\AddCandidateToWaitingListCommandHandler;
use App\Application\AssignCandidateToActivityCommandHandler;
use App\Application\Command\AddCandidateToWaitingListCommand;
use App\Application\Command\AssignCandidateToActivityCommand;
use App\Domain\ActivityRepository;
use App\Domain\Exception\ParticipantAlreadyAssignedToDesiredActivityCount;
use App\Domain\NullActivity;
use App\Domain\Read\ParticipantDesiredAssignments;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\IntValueObject;
use App\Domain\ValueObject\SequenceNumber;
use App\Domain\ValueObject\StringValueObject;
use App\Domain\WaitingCandidateRepository;
use App\Tests\Infrastructure\Stubs\ActivityStubBuilder;
use App\Tests\Infrastructure\Stubs\StubCandidateId;
use App\Tests\Infrastructure\Stubs\StubCandidateNumber;
use App\Tests\Infrastructure\Stubs\StubSequenceNumber;
use ParticipantDesiredAssignmentsRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddCandidateToWaitingListCommandHandlerTest extends TestCase
{
    private AddCandidateToWaitingListCommandHandler $addCandidateRequestToWaitingListCommandHandler;
    private $activityRepository;
    private WaitingCandidateRepository $waitingCandidateRepository;

    public function test_cant_assign_a_candidate_request_of_non_existing_activity_to_waiting_list()
    {
        $this->activityRepository->method('findByCode')->willReturn(NullActivity::createNull());

        $command = new AddCandidateToWaitingListCommand(
            StubCandidateId::random(),
            ActivityCode::fromString('non-existing-activity')->value(),
            StubCandidateNumber::random()->value(),
            StubSequenceNumber::random()->value()
        );
        $this->expectException(InvalidArgumentException::class);

        $this->addCandidateRequestToWaitingListCommandHandler->__invoke($command);
    }

    public function test_waiting_candidate_is_added()
    {
        $activity = ActivityStubBuilder::create()->build();

        $this->activityRepository->method('findByCode')->willReturn(
            $activity
        );

        $command = new AddCandidateToWaitingListCommand(
            StubCandidateId::random(),
            ActivityCode::fromString('non-existing-activity')->value(), 
            StubCandidateNumber::random()->value(),
            StubSequenceNumber::random()->value()
        );

        $this->addCandidateRequestToWaitingListCommandHandler->__invoke($command);

        $this->addToAssertionCount(1);
    }

    public function test_cant_assign_a_candidate_when_already_assigned_to_desired_activity_count()
    {
        $activity = ActivityStubBuilder::create()
            ->withCapacity(Capacity::fromInt(10))
            ->withParticipants([])
            ->build();

        $this->activityRepository->method('findByCode')->willReturn($activity);

        $candidateNumber = CandidateNumber::fromInt(1);

        $this->participantDesiredAssignments->method('findByCandidateNumber')->with($candidateNumber)->willReturn(
            new ParticipantDesiredAssignments($candidateNumber, DesiredActivityCount::fromInt(2), IntValueObject::fromInt(2))
        );

        $command = new AssignCandidateToActivityCommand(
            ActivityCode::fromString('existing-activity')->value(),
            Email::fromString('candidate_email@email.com')->value(),
            StringValueObject::fromString('Candidate name')->value(),
            $candidateNumber->value(),
            StubCandidateId::random()->value(),
            SequenceNumber::initial()->value()
        );
        $this->expectException(ParticipantAlreadyAssignedToDesiredActivityCount::class);

        $this->assignCandidateToActivityCommandHandler->__invoke($command);
    }

    protected function setUp(): void
    {
        $this->activityRepository = $this->createMock(ActivityRepository::class);
        $this->waitingCandidateRepository = $this->createMock(WaitingCandidateRepository::class);

        $this->waitingCandidateRepository = $this->createMock(WaitingCandidateRepository::class);

        $this->participantDesiredAssignmentRepository = $this->createMock(ParticipantDesiredAssignmentsRepository::class);

        $this->addCandidateRequestToWaitingListCommandHandler =
            new AddCandidateToWaitingListCommandHandler(
                $this->activityRepository,
                $this->waitingCandidateRepository
            );

        $this->assignCandidateToActivityCommandHandler =
            new AssignCandidateToActivityCommandHandler(
                $this->activityRepository,
                $this->participantDesiredAssignmentRepository
            );
    }

}