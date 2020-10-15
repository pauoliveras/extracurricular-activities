<?php

namespace App\Domain\Read;

use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\IntValueObject;

class ParticipantDesiredAssignments
{
    private Id $candidateId;
    private DesiredActivityCount $desiredActivityCount;
    private IntValueObject $currentAssignmentCount;

    public function __construct(Id $candidateId, DesiredActivityCount $desiredActivityCount, IntValueObject $currentAssignmentCount)
    {
        $this->candidateId = $candidateId;
        $this->desiredActivityCount = $desiredActivityCount;
        $this->currentAssignmentCount = $currentAssignmentCount;
    }

    public function desiredActivityCount(): DesiredActivityCount
    {
        return $this->desiredActivityCount;
    }

    public function fulfilled(): bool
    {
        return $this->currentAssignmentCount->equalsTo($this->desiredActivityCount);
    }

}