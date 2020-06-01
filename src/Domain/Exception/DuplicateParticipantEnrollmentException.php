<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\StringValueObject;
use Exception;

class DuplicateParticipantEnrollmentException extends Exception
{

    public static function ofParticipant(StringValueObject $participantName, ActivityCode $activityCode)
    {
        return new self(
            sprintf('Participant %s is already enrolled in activity %s', $participantName->value(), $activityCode->value())
        );
    }
}