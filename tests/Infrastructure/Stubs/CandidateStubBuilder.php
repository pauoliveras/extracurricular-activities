<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;
use App\Domain\ValueObject\CandidateNumber;
use App\Tests\Infrastructure\Service\ReflectionEntityManager;

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
        $reflectionEntityManager = new ReflectionEntityManager();

        $candidate = $reflectionEntityManager->buildObject(
            Candidate::class,
            [
                'id' => $this->id,
                'email' => $this->email->value(),
                'candidateName' => $this->candidateName->value(),
                'group' => $this->candidateGroup->value(),
                'requestedActivities' => $this->requestedActivitiesList,
                'candidateNumber' => $this->candidateNumber->value()
            ]
        );
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