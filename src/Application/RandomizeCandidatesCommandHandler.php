<?php

namespace App\Application;

use App\Application\Command\RandomizeCandidatesCommand;
use App\Domain\CandidateRepository;
use App\Domain\ValueObject\CandidateNumber;

class RandomizeCandidatesCommandHandler
{
    private $candidateRepository;
    private array $randomizedCandidateEmails = [];

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(RandomizeCandidatesCommand $command)
    {
        $candidates = $this->candidateRepository->findAll();

        $numbers = range(1, $this->candidateRepository->countDistinctEmails());

        /** @var Candidate $candidate */
        foreach ($candidates as $candidate) {

            $randomNumber = $this->randomizedCandidateEmails[$candidate->email()->value()] ?? null;

            if (!$randomNumber) {
                $randomKey = array_rand($numbers);
                $randomNumber = $numbers[$randomKey];
                unset($numbers[$randomKey]);
            }

            $candidate->assignNumber(CandidateNumber::fromInt($randomNumber));
            $this->candidateRepository->save($candidate);

            $this->randomizedCandidateEmails[$candidate->email()->value()] = $randomNumber;
        }
    }

}