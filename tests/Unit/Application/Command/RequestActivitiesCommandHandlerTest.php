<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\Command\RequestActivitiesCommandHandler;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\RequestedActivty;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandHandlerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $candidateRepository;
    private $requestActivitiesCommandHandler;

    protected function setUp(): void
    {
        $this->candidateRepository = $this->createMock(CandidateRepository::class);
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

        $this->candidateRepository->expects($this->once())->method('save')->with(
            new Candidate(
                Email::fromString($command->email()),
                StringValueObject::fromString($command->candidateName()),
                StringValueObject::fromString($command->group()),
                [
                    new RequestedActivty(ActivityCode::fromString($command->orderedOtions()[0]), RequestOrder::fromInt(1)),
                    new RequestedActivty(ActivityCode::fromString($command->orderedOtions()[1]), RequestOrder::fromInt(2)),
                    new RequestedActivty(ActivityCode::fromString($command->orderedOtions()[2]), RequestOrder::fromInt(3)),
                ]
            )
        );

        $this->requestActivitiesCommandHandler->__invoke($command);
    }

}