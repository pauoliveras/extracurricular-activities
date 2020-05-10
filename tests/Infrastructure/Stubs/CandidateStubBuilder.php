<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;

class CandidateStubBuilder
{
    private $id;
    private $email;
    private $candidateName;
    private $candidateGroup;
    private $requestedActivitiesList;

    /**
     *
     * CandidateStubBuilder constructor.
     */
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
    }

    public function build(): Candidate
    {
        return new Candidate(
            $this->id,
            $this->email,
            $this->candidateName,
            $this->candidateGroup,
            $this->requestedActivitiesList
        );
    }

    public function withRandomActivities(array $activities)
    {
        $this->requestedActivitiesList = StubRequestedActivitiesList::randomWith($activities);

        return $this;
    }
}