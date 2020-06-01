<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;
use App\Domain\ValueObject\CandidateNumber;

class CandidateStubBuilder
{
    private $id;
    private $email;
    private $candidateName;
    private $candidateGroup;
    private $requestedActivitiesList;
    private $candidateNumber;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->id = StubCandidateId::random();
        $this->email = StubEmail::random();
        $this->candidateName = StubCandidateName::random();
        $this->candidateGroup = StubCandidateGroup::random();
        $this->requestedActivitiesList = StubRequestedActivitiesList::random();
        $this->candidateNumber = null;
    }

    public function build(): Candidate
    {
        $candidate = new Candidate(
            $this->id,
            $this->email,
            $this->candidateName,
            $this->candidateGroup,
            $this->requestedActivitiesList
        );

        if ($this->candidateNumber) {
            $candidate->assignNumber($this->candidateNumber);
        }

        $this->reset();

        return $candidate;
    }

    public function withRandomActivities(array $activities)
    {
        $this->requestedActivitiesList = StubRequestedActivitiesList::randomWith($activities);

        return $this;
    }

    public function withNumber(CandidateNumber $candidateNumber)
    {
        $this->candidateNumber = $candidateNumber;

        return $this;
    }
}