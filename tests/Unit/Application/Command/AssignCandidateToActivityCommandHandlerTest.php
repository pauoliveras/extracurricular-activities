<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\AssignCandidateToActivityCommandHandler;
use App\Application\Command\AssignCandidateToActivityCommand;
use App\Domain\ActivityRepository;
use App\Domain\NullActivity;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;
use App\Tests\Infrastructure\Stubs\ActivityStubBuilder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AssignCandidateToActivityCommandHandlerTest extends TestCase
{
    private AssignCandidateToActivityCommandHandler $assignCandidateToActivityCommandHandler;
    private $activityRepository;

    public function test_cant_assign_a_candidate_to_non_existing_activity()
    {
        $this->activityRepository->method('findByCode')->willReturn(NullActivity::createNull());

        $command = new AssignCandidateToActivityCommand(
            ActivityCode::fromString('non-existing-activity')->value(),
            Email::fromString('candidate_email@email.com')->value(),
            StringValueObject::fromString('Candidate name')->value()
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
            StringValueObject::fromString('Candidate name')->value()
        );

        $this->assignCandidateToActivityCommandHandler->__invoke($command);

        $this->addToAssertionCount(1);
    }

    protected function setUp(): void
    {
        $this->activityRepository = $this->createMock(ActivityRepository::class);
        $this->assignCandidateToActivityCommandHandler =
            new AssignCandidateToActivityCommandHandler(
                $this->activityRepository
            );
    }

}