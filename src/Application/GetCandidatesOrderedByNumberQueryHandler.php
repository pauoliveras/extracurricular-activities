<?php

namespace App\Application;

use App\Application\Query\GetCandidatesOrderedByNumberQuery;
use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Domain\CandidateRepository;
use InvalidArgumentException;

class GetCandidatesOrderedByNumberQueryHandler
{
    private $repository;

    public function __construct(CandidateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetCandidatesOrderedByNumberQuery $query): CandidateCollection
    {
        $candidates = $this->repository->findAll();

        if ($candidates->isEmpty()) {
            return new CandidateCollection([]);
        }

        if ($query->number()->value() > $candidates->count()) {
            throw new InvalidArgumentException('Invalid number');
        }

        $orderedCandidates = $this->getOrderedCandidatesByNumber($candidates);

        $firstBlock = array_slice($orderedCandidates, 0, $query->number()->value() - 1);
        $secondBlock = array_slice($orderedCandidates, $query->number()->value() - 1);

        return new CandidateCollection(array_merge($secondBlock, $firstBlock));
    }

    protected function getOrderedCandidatesByNumber(CandidateCollection $candidates): array
    {
        $arrayIterator = $candidates->getIterator();
        $arrayIterator->uasort(
            function (Candidate $first, Candidate $second) {
                return $first->number()->value() > $second->number()->value();
            }
        );

        return iterator_to_array($arrayIterator);
    }

}