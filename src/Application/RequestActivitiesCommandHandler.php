<?php


namespace App\Application;

use App\Application\Command\RequestActivitiesCommand;
use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;

class RequestActivitiesCommandHandler
{
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(RequestActivitiesCommand $command)
    {
        $this->checkRequestedOptionsAreNotEmpty($command);

        $order = 1;

        $candidate = new Candidate(
            $this->candidateRepository->nextId(),
            Email::fromString($command->email()),
            StringValueObject::fromString($command->candidateName()),
            StringValueObject::fromString($command->group()),
            array_map(function ($requestedActivityCode) use (&$order) {
                return [ActivityCode::fromString($requestedActivityCode), RequestOrder::fromInt($order++)];
            }, $command->orderedOtions())
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
}