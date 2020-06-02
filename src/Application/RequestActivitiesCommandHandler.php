<?php


namespace App\Application;

use App\Application\Command\RequestActivitiesCommand;
use App\Domain\ActivityRepository;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\Exception\DuplicateCandidateRequestException;
use App\Domain\RequestedActivitiesList;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\Email;
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
        $this->checkCandidateHasntPlacedAnyRequest($command);

        $requestedActivities = RequestedActivitiesList::createFromArray($command->orderedOtions());

        $this->ensureRequestedActivitiesExist($requestedActivities);

        $candidate = new Candidate(
            $this->candidateRepository->nextId(),
            CandidateCode::fromString($command->candidateCode()),
            Email::fromString($command->email()),
            StringValueObject::fromString($command->candidateName()),
            StringValueObject::fromString($command->group()),
            $requestedActivities
        );

        $this->candidateRepository->save($candidate);
    }

    protected function ensureRequestedActivitiesExist(RequestedActivitiesList $orderedOptions): void
    {
        foreach ($orderedOptions as $requestedActivityCode) {
            $activity = $this->activityRepository->findByCode($requestedActivityCode);
            if ($activity->isNull()) {
                throw new InvalidArgumentException(
                    sprintf('Activity of code %s not found', $requestedActivityCode)
                );
            }
        }
    }

    protected function checkCandidateHasntPlacedAnyRequest(RequestActivitiesCommand $command)
    {
        $candidate = $this->candidateRepository->findByCode(CandidateCode::fromString($command->candidateCode()));

        if (!$candidate->isNull()) {
            throw DuplicateCandidateRequestException::candidate(CandidateCode::fromString($command->candidateCode()));
        }
    }

}