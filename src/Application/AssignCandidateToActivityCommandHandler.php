<?php

namespace App\Application;

use App\Domain\ActivityRepository;
use InvalidArgumentException;

class AssignCandidateToActivityCommandHandler
{
    private ActivityRepository $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function __invoke(Command\AssignCandidateToActivityCommand $command)
    {
        $activity = $this->activityRepository->findByCode($command->activityCode());

        if ($activity->isNull()) {
            throw new InvalidArgumentException(sprintf('Activity of code %s does not exist', $command->activityCode()->value()));
        }

        $activity->enroll($command->candidateEmail(), $command->candidateName(), $command->candidateNumber(), $command->sequenceNumber());

        $this->activityRepository->save($activity);
    }
}