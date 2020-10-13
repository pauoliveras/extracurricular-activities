<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Activity;
use App\Domain\Exception\DuplicateParticipantEnrollmentException;
use App\Domain\Exception\ParticipantEnrollmentClosedException;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\SequenceNumber;
use App\Domain\ValueObject\StringValueObject;
use App\Tests\Infrastructure\Stubs\StubActivityCode;
use App\Tests\Infrastructure\Stubs\StubCandidateId;
use App\Tests\Infrastructure\Stubs\StubId;
use PHPUnit\Framework\TestCase;

class ActivityEnroleParticipantTest extends TestCase
{
    public function test_a_participant_can_be_enrolled()
    {
        $activity = Activity::create(
            StubId::random(),
            StubActivityCode::random(),
            Capacity::fromInt(10)
        );

        $this->assertCount(0, $activity->participants());

        $activity->enroll(
            Email::fromString('participant@email.com'),
            StringValueObject::fromString('Participant name'),
            CandidateNumber::fromInt(1),
            SequenceNumber::initial(),
            StubCandidateId::random()
        );

        $this->addToAssertionCount(1);
    }

    public function test_enrolling_one_participant_increases_participant_count()
    {
        $activity = Activity::create(
            StubId::random(),
            StubActivityCode::random(),
            Capacity::fromInt(10)
        );

        $this->assertCount(0, $activity->participants());
        $activity->enroll(
            Email::fromString('participant@email.com'),
            StringValueObject::fromString('Participant name'),
            CandidateNumber::fromInt(1),
            SequenceNumber::initial(),
            StubCandidateId::random()
        );

        $this->assertCount(1, $activity->participants());
    }

    public function test_enrolling_same_participant_twice_is_not_allowed()
    {
        $activity = Activity::create(
            StubId::random(),
            StubActivityCode::random(),
            Capacity::fromInt(10)
        );

        $candidateId = StubCandidateId::random();
        $activity->enroll(
            Email::fromString('participant1@email.com'),
            StringValueObject::fromString('Participant 1 name'),
            CandidateNumber::fromInt(1),
            SequenceNumber::initial(),
            $candidateId
        );

        $this->expectException(DuplicateParticipantEnrollmentException::class);

        $activity->enroll(
            Email::fromString('participant1@email.com'),
            StringValueObject::fromString('Participant 1 name'),
            CandidateNumber::fromInt(1),
            SequenceNumber::initial(),
            $candidateId
        );
    }

    public function test_cant_enroll_participant_when_activity_is_full()
    {
        $activity = Activity::create(
            StubId::random(),
            StubActivityCode::random(),
            Capacity::fromInt(1)
        );

        $activity->enroll(
            Email::fromString('participant1@email.com'),
            StringValueObject::fromString('Participant 1 name'),
            CandidateNumber::fromInt(1),
            SequenceNumber::initial(),
            StubCandidateId::random()
        );

        $this->expectException(ParticipantEnrollmentClosedException::class);

        $activity->enroll(
            Email::fromString('participant2@email.com'),
            StringValueObject::fromString('Participant 2 name'),
            CandidateNumber::fromInt(2),
            SequenceNumber::initial(),
            StubCandidateId::random()
        );
    }
}