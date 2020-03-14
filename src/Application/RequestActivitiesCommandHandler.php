<?php


namespace App\Application;

use App\Application\Command\RequestActivitiesCommand;
use App\Domain\ActivityRepository;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\Exception\DuplicateCandidateRequestException;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;

class RequestActivitiesCommandHandler
{
    private $candidateRepository;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    public function __construct(
        CandidateRepository $candidateRepository,
        ActivityRepository $activityRepository
    )
    {
        $this->candidateRepository = $candidateRepository;
        $this->activityRepository = $activityRepository;
    }

    public function __invoke(RequestActivitiesCommand $command)
    {
        $this->checkRequestedOptionsAreNotEmpty($command);

        $this->checkCandidateHasntPlacedAnyRequest($command);

        $requestedActivities = $this->createOrderedRequestedActivitiesFromCommand($command->orderedOtions());

        $candidate = new Candidate(
            $this->candidateRepository->nextId(),
            Email::fromString($command->email()),
            StringValueObject::fromString($command->candidateName()),
            StringValueObject::fromString($command->group()),
            $requestedActivities
        );

        $this->candidateRepository->save($candidate);
    }

    /**
     * @param RequestActivitiesCommand $command
     */
    protected function checkRequestedOptionsAreNotEmpty(RequestActivitiesCommand $command): void
    {
        if (empty($command->orderedOtions())) {
            throw new InvalidArgumentException('Candidate must provide at least one requested option');
        }
    }

    protected function createOrderedRequestedActivitiesFromCommand(array $orderedOptions): array
    {
        $requestedActivities = [];
        $order = 1;

        foreach ($orderedOptions as $requestedActivityCode) {
            $activity = $this->activityRepository->findByCode(ActivityCode::fromString($requestedActivityCode));

            if ($activity->isNull()) {
                throw new InvalidArgumentException(
                    sprintf('Activity of code %s not found', $requestedActivityCode)
                );
            }
            $requestedActivities[] = [ActivityCode::fromString($requestedActivityCode), RequestOrder::fromInt($order++)];
        }
        return $requestedActivities;
    }

    protected function checkCandidateHasntPlacedAnyRequest(RequestActivitiesCommand $command)
    {
        $candidate = $this->candidateRepository->findByEmail(Email::fromString($command->email()));

        if (!$candidate->isNull()) {
            throw DuplicateCandidateRequestException::candidate(Email::fromString($command->email()));
        }
    }
}