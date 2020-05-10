<?php

namespace App\Application;

use App\Application\Command\RandomizeCandidatesCommand;
use App\Domain\CandidateRepository;
use App\Domain\ValueObject\CandidateNumber;

class RandomizeCandidatesCommandHandler
{
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(RandomizeCandidatesCommand $command)
    {
        $candidates = $this->candidateRepository->findAll();

        $numbers = range(1, $candidates->count());

        foreach ($candidates as $candidate) {

            $randomKey = array_rand($numbers);

            $candidate->assignNumber(CandidateNumber::fromInt($numbers[$randomKey]));
            $this->candidateRepository->save($candidate);
            unset($numbers[$randomKey]);
        }
    }

}