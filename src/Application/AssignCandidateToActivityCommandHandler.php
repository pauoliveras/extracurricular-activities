<?php

namespace App\Application;

use App\Domain\ActivityRepository;
use App\Domain\Exception\ParticipantAlreadyAssignedToDesiredActivityCount;
use App\Domain\Read\ParticipantDesiredAssignmentsRepository;
use InvalidArgumentException;

class AssignCandidateToActivityCommandHandler
{
    private ActivityRepository $activityRepository;
    private ParticipantDesiredAssignmentsRepository $participantDesiredAssignmentsRepository;

    public function __construct(
        ActivityRepository $activityRepository,
        ParticipantDesiredAssignmentsRepository $participantDesiredAssignmentsRepository
    )
    {
        $this->activityRepository = $activityRepository;
        $this->participantDesiredAssignmentsRepository = $participantDesiredAssignmentsRepository;
    }

    public function __invoke(Command\AssignCandidateToActivityCommand $command)
    {
        $activity = $this->activityRepository->findByCode($command->activityCode());

        if ($activity->isNull()) {
            throw new InvalidArgumentException(sprintf('Activity of code %s does not exist', $command->activityCode()->value()));
        }

        $participantDesiredAssignments = $this->participantDesiredAssignmentsRepository->findByCandidateId($command->candidateId());

        if ($participantDesiredAssignments->fulfilled()) {
            throw ParticipantAlreadyAssignedToDesiredActivityCount::desiredParticipanActivityCount($command->candidateName(), $participantDesiredAssignments->desiredActivityCount());
        }

        $activity->enroll($command->candidateEmail(), $command->candidateName(), $command->candidateNumber(), $command->sequenceNumber(), $command->candidateId());

        $this->activityRepository->save($activity);
    }
}