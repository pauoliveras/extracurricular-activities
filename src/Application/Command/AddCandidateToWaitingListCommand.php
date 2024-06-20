<?php

namespace App\Application\Command;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\SequenceNumber;

class AddCandidateToWaitingListCommand
{
    private string $candidateId;
    private string $activityCode;
    private int $candidateNumber;
    private int $sequenceNumber;

    public function __construct(
        string $candidateId,
        string $activityCode,
        int $candidateNumber,
        int $sequenceNumber
    )
    {
        $this->candidateId = $candidateId;
        $this->activityCode = $activityCode;
        $this->candidateNumber = $candidateNumber;
        $this->sequenceNumber = $sequenceNumber;
    }

    public function activityCode(): ActivityCode
    {
        return ActivityCode::fromString($this->activityCode);
    }

    public function candidateId(): Id
    {
        return Id::fromString($this->candidateId);
    }

    public function candidateNumber(): CandidateNumber
    {
        return CandidateNumber::fromInt($this->candidateNumber);
    }
    public function sequenceNumber(): SequenceNumber
    {
        return SequenceNumber::fromInt($this->sequenceNumber);
    }
}