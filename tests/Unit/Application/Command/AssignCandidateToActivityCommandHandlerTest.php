<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\AssignCandidateToActivityCommandHandler;
use App\Application\Command\AssignCandidateToActivityCommand;
use App\Domain\ActivityRepository;
use App\Domain\Exception\ParticipantAlreadyAssignedToDesiredActivityCount;
use App\Domain\NullActivity;
use App\Domain\Read\ParticipantDesiredAssignments;
use App\Domain\Read\ParticipantDesiredAssignmentsRepository;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\IntValueObject;
use App\Domain\ValueObject\SequenceNumber;
use App\Domain\ValueObject\StringValueObject;
use App\Tests\Infrastructure\Stubs\ActivityStubBuilder;
use App\Tests\Infrastructure\Stubs\StubCandidateId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssignCandidateToActivityCommandHandlerTest extends TestCase
{
    private AssignCandidateToActivityCommandHandler $assignCandidateToActivityCommandHandler;
    private $activityRepository;
    private ParticipantDesiredAssignmentsRepository $participantDesiredAssignments;

    public function test_cant_assign_a_candidate_to_non_existing_activity()
    {
        $this->activityRepository->method('findByCode')->willReturn(NullActivity::createNull());

        $command = new AssignCandidateToActivityCommand(
            ActivityCode::fromString('non-existing-activity')->value(),
            Email::fromString('candidate_email@email.com')->value(),
            StringValueObject::fromString('Candidate name')->value(),
            CandidateNumber::fromInt(1)->value(),
            StubCandidateId::random(),
            SequenceNumber::initial()->value()
        );
        $this->expectException(InvalidArgumentException::class);

        $this->assignCandidateToActivityCommandHandler->__invoke($command);
    }

    public function test_candidate_is_assigned_to_an_activity_with_vacancies()
    {
        $activity = ActivityStubBuilder::create()
            ->withCapacity(Capacity::fromInt(10))
            ->withParticipants([])
            ->build();

        $this->activityRepository->method('findByCode')->willReturn(
            $activity
        );

        $command = new AssignCandidateToActivityCommand(
            ActivityCode::fromString('existing-activity')->value(),
            Email::fromString('candidate_email@email.com')->value(),
            StringValueObject::fromString('Candidate name')->value(),
            CandidateNumber::fromInt(1)->value(),
            StubCandidateId::random(),
            SequenceNumber::initial()->value()
        );

        $this->assignCandidateToActivityCommandHandler->__invoke($command);

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
        $this->participantDesiredAssignments = $this->createMock(ParticipantDesiredAssignmentsRepository::class);
        $this->assignCandidateToActivityCommandHandler =
            new AssignCandidateToActivityCommandHandler(
                $this->activityRepository,
                $this->participantDesiredAssignments
            );
    }

}