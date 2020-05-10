<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Domain\CandidateRepository;
use App\Domain\NullCandidate;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;

class InMemoryCandidateRepository implements CandidateRepository
{
    private $candidates = [];
    private $savedCandidates = 0;

    public function save(Candidate $candidate)
    {
        $this->candidates[(string)$candidate->email()] = $candidate;
        $this->savedCandidates++;
    }

    public function findByEmail(Email $email): Candidate
    {
        return isset($this->candidates[(string)$email]) ? $this->candidates[(string)$email] : NullCandidate::create();
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

}