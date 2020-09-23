<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Candidate;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;

class CandidateStubBuilder
{
    private $id;
    private $email;
    private $candidateName;
    private $candidateGroup;
    private $requestedActivitiesList;
    private $candidateNumber;
    private $code;
    private ?DesiredActivityCount $desiredActivityCount;

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
        $this->code = CandidateCode::fromString($this->candidateName->value() . "|" . $this->candidateGroup->value());
        $this->desiredActivityCount = StubDesiredActivityCount::random();
    }

    public static function create()
    {
        return new self();
    }

    public function build(): Candidate
    {
        $candidate = new Candidate(
            $this->id,
            $this->code,
            $this->email,
            $this->candidateName,
            $this->candidateGroup,
            $this->requestedActivitiesList,
            $this->desiredActivityCount
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

    public function withActivities(array $activities)
    {
        $this->requestedActivitiesList = StubRequestedActivitiesList::create($activities);

        return $this;
    }

    public function withName(StringValueObject $candidateName)
    {
        $this->candidateName = $candidateName;

        return $this;
    }

    public function withEmail(Email $email)
    {
        $this->email = $email;

        return $this;
    }
}