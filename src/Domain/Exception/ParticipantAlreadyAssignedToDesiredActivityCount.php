<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\StringValueObject;
use Exception;

class ParticipantAlreadyAssignedToDesiredActivityCount extends Exception
{

    public static function desiredParticipanActivityCount(StringValueObject $candidateName, DesiredActivityCount $desiredActivityCount)
    {
        return new self(
            sprintf(
                'Participant %s has already been assigned to desired activity count(%d)',
                $candidateName->value(),
                $desiredActivityCount->value()
            )
        );
    }
}