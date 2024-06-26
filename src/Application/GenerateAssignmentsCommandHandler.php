<?php

namespace App\Application;

use App\Application\Command\AddCandidateToWaitingListCommand;
use App\Application\Command\AssignCandidateToActivityCommand;
use App\Application\Command\GenerateAssignmentsCommand;
use App\Application\Query\GetCandidatesOrderedByNumberQuery;
use App\Domain\Exception\ParticipantAlreadyAssignedToDesiredActivityCount;
use App\Domain\Exception\ParticipantEnrollmentClosedException;
use App\Domain\RequestedActivity;
use App\Domain\ValueObject\SequenceNumber;

class GenerateAssignmentsCommandHandler
{

    private GetCandidatesOrderedByNumberQueryHandler $getOrderedCandidates;
    private AssignCandidateToActivityCommandHandler $assignCandidateToActivity;
    private int $processedRequests = 0;
    private array $candidateRequests;
    private int $totalRequestCount = 0;
    /**
     * @var AddCandidateToWaitingListCommandHandler
     */
    private AddCandidateToWaitingListCommandHandler $addCandidateToWaitingList;

    public function __construct(
        GetCandidatesOrderedByNumberQueryHandler $getOrderedCandidates,
        AssignCandidateToActivityCommandHandler $assignCandidateToActivity,
        AddCandidateToWaitingListCommandHandler $addCandidateToWaitingList
    )
    {
        $this->getOrderedCandidates = $getOrderedCandidates;
        $this->assignCandidateToActivity = $assignCandidateToActivity;
        $this->addCandidateToWaitingList = $addCandidateToWaitingList;
    }

    public function __invoke(GenerateAssignmentsCommand $command)
    {
        $candidates = $this->getOrderedCandidates->__invoke(new GetCandidatesOrderedByNumberQuery($command->luckyDrawNumber()));
        $candidatePosition = 1;
        foreach ($candidates as $candidate) {
            $this->candidateRequests[$candidatePosition] =
                array_combine(
                    array_map(
                        function (RequestedActivity $requestedActivty) {
                            return (string)$requestedActivty->code();
                        },
                        $candidate->requestedActivities()->toArray()
                    ),
                    $candidate->requestedActivities()->toArray()
                );
            $this->totalRequestCount += count($candidate->requestedActivities()->toArray());
            $candidatePosition++;
        }
        $sequenceNumber = SequenceNumber::initial();
        while ($this->processedRequests < $this->totalRequestCount) {
            $candidatePosition = 0;
            foreach ($candidates as $candidate) {
                $candidatePosition++;
                foreach ($this->candidateRequests[$candidatePosition] as $activityCode => $requestedActivity) {

                    unset($this->candidateRequests[$candidatePosition][$activityCode]);
                    $this->processedRequests++;

                    try {
                        $this->assignCandidateToActivity->__invoke(
                            new AssignCandidateToActivityCommand(
                                $requestedActivity->code(),
                                $candidate->email()->value(),
                                $candidate->candidateName()->value(),
                                $candidate->number()->value(),
                                $candidate->id(),
                                $sequenceNumber->value()
                            )
                        );
                        $sequenceNumber = $sequenceNumber->next();

                        continue 2;
                    } catch (ParticipantEnrollmentClosedException $exception) {
                        $this->addCandidateToWaitingList->__invoke(
                            new AddCandidateToWaitingListCommand(
                                $candidate->id(),
                                $requestedActivity->code(),
                                $candidate->number()->value(),
                                $sequenceNumber->value()
                            )
                        );

                        continue;
                    } catch (ParticipantAlreadyAssignedToDesiredActivityCount $exception) {
                        continue 2;
                    }
                }
            }
        }
    }
}