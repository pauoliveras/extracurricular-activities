<?php

namespace App\Application\Command;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;

class AddCandidateToWaitingListCommand
{
    private string $candidateId;
    private string $activityCode;

    public function __construct(
        string $candidateId,
        string $activityCode
    )
    {
        $this->candidateId = $candidateId;
        $this->activityCode = $activityCode;
    }

    public function activityCode(): ActivityCode
    {
        return ActivityCode::fromString($this->activityCode);
    }

    public function candidateId(): Id
    {
        return Id::fromString($this->candidateId);
    }
}