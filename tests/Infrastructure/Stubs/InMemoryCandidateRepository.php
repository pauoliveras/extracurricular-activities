<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Domain\CandidateRepository;
use App\Domain\NullCandidate;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\Id;

class InMemoryCandidateRepository implements CandidateRepository
{
    private $candidates = [];
    private $savedCandidates = 0;

    public function save(Candidate $candidate)
    {
        $this->candidates[(string)$candidate->candidateCode()] = $candidate;
        $this->savedCandidates++;
    }

    public function findByCode(CandidateCode $code): Candidate
    {
        return isset($this->candidates[(string)$code]) ? $this->candidates[(string)$code] : NullCandidate::create();
    }

    public function nextId(): Id
    {
        return Id::next();
    }

    public function findAll(): CandidateCollection
    {
        return new CandidateCollection($this->candidates);
    }

    public function reset(): void
    {
        $this->savedCandidates = 0;
    }

    public function savedCandidates(): int
    {
        return $this->savedCandidates;
    }

    public function countDistinctEmails(): int
    {
        $distinctEmails = [];
        foreach ($this->candidates as $candidate) {
            $distinctEmails[$candidate->email()->value()] = true;
        }

        return count($distinctEmails);
    }
}