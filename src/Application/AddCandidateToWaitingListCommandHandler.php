<?php

namespace App\Application;

use App\Domain\ActivityRepository;
use App\Domain\WaitingCandidate;
use App\Domain\WaitingCandidateRepository;
use InvalidArgumentException;

class AddCandidateToWaitingListCommandHandler
{
    private ActivityRepository $activityRepository;
    private WaitingCandidateRepository $waitingCandidateRepository;

    public function __construct(
        ActivityRepository $activityRepository,
        WaitingCandidateRepository $waitingCandidateRepository
    )
    {
        $this->activityRepository = $activityRepository;
        $this->waitingCandidateRepository = $waitingCandidateRepository;
    }

    public function __invoke(Command\AddCandidateToWaitingListCommand $command)
    {
        $activity = $this->activityRepository->findByCode($command->activityCode());

        if ($activity->isNull()) {
            throw new InvalidArgumentException(sprintf('Activity of code %s does not exist', $command->activityCode()->value()));
        }

        $waitingCandidate = $this->waitingCandidateRepository->findByCandidateId($command->candidateId());

        if (!$waitingCandidate) {
            $waitingCandidate = WaitingCandidate::register($command->candidateId(), $command->activityCode());
        } else {
            $waitingCandidate->addActivity($command->activityCode());
        }

        $this->waitingCandidateRepository->save($waitingCandidate);
    }
}