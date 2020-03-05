<?php


namespace App\Application\Command;


use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\RequestedActivty;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;

class RequestActivitiesCommandHandler
{
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(RequestActivitiesCommand $command)
    {
        $order = 1;

        $candidate = new Candidate(
            Email::fromString($command->email()),
            StringValueObject::fromString($command->candidateName()),
            StringValueObject::fromString($command->group()),
            array_map(function($requestedActivityCode) use (&$order) {

                return new RequestedActivty(ActivityCode::fromString($requestedActivityCode), RequestOrder::fromInt($order++));
            }, $command->orderedOtions())
        );

        $this->candidateRepository->save($candidate);
    }
}