<?php

namespace App\Application;

use App\Application\Command\AssignCandidateToActivityCommand;
use App\Application\Command\GenerateAssignmentsCommand;
use App\Application\Query\GetCandidatesOrderedByNumberQuery;
use App\Domain\Exception\ParticipantEnrollmentClosedException;
use App\Domain\RequestedActivty;

class GenerateAssignmentsCommandHandler
{

    private GetCandidatesOrderedByNumberQueryHandler $getOrderedCandidates;
    private AssignCandidateToActivityCommandHandler $assignCandidateToActivity;
    private int $processedRequests = 0;
    private array $candidateRequests;
    private int $totalRequestCount = 0;

    public function __construct(GetCandidatesOrderedByNumberQueryHandler $getOrderedCandidates, AssignCandidateToActivityCommandHandler $assignCandidateToActivity)
    {
        $this->getOrderedCandidates = $getOrderedCandidates;
        $this->assignCandidateToActivity = $assignCandidateToActivity;
    }

    public function __invoke(GenerateAssignmentsCommand $command)
    {
        $candidates = $this->getOrderedCandidates->__invoke(new GetCandidatesOrderedByNumberQuery($command->luckyDrawNumber()));

        foreach ($candidates as $candidate) {
            $this->candidateRequests[$candidate->number()->value()] =
                array_combine(
                    array_map(
                        function (RequestedActivty $requestedActivty) {
                            return (string)$requestedActivty->code();
                        },
                        $candidate->requestedActivities()->toArray()
                    ),
                    $candidate->requestedActivities()->toArray()
                );
            $this->totalRequestCount += count($candidate->requestedActivities()->toArray());
        }

        while ($this->processedRequests < $this->totalRequestCount) {
            foreach ($candidates as $candidate) {
                foreach ($this->candidateRequests[$candidate->number()->value()] as $activityCode => $requestedActivity) {

                    unset($this->candidateRequests[$candidate->number()->value()][$activityCode]);
                    $this->processedRequests++;
                    try {
                        $this->assignCandidateToActivity->__invoke(
                            new AssignCandidateToActivityCommand(
                                $requestedActivity->code(),
                                $candidate->email()->value(),
                                $candidate->candidateName()->value(),
                                $candidate->number()->value()
                            )
                        );
                        continue 2;
                    } catch (ParticipantEnrollmentClosedException $exception) {
                        continue;
                    }
                }
            }
        }
    }
}