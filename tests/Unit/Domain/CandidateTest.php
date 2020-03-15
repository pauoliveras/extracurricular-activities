<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Candidate;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CandidateTest extends TestCase
{
    public function test_a_candidate_can_be_created_with_a_requested_activity()
    {
        $requestedActvities = [
            [ActivityCode::fromString('activity_1'), RequestOrder::fromInt(1)],
        ];

        $candidate = new Candidate(
            Id::next(),
            Email::fromString('email@test.com'),
            StringValueObject::fromString('Candidate name'),
            StringValueObject::fromString('group name'),
            $requestedActvities
        );

        $this->assertEquals('email@test.com', $candidate->email()->value());
        $this->assertEquals('Candidate name', $candidate->candidateName()->value());
        $this->assertEquals('group name', $candidate->candidateGroup()->value());
        $this->assertNotEmpty($candidate->requestedActivities());
    }

    public function test_a_candidate_has_to_be_created_with_at_least_one_request()
    {
        $this->expectException(InvalidArgumentException::class);

        new Candidate(
            Id::next(),
            Email::fromString('email@test.com'),
            StringValueObject::fromString('Candidate name'),
            StringValueObject::fromString('group name'),
            []
        );
    }

    public function test_a_candidate_can_not_be_created_with_repeated_activities()
    {
        $this->expectException(InvalidArgumentException::class);

        $requestedActvities = [
            [ActivityCode::fromString('activity_1'), RequestOrder::fromInt(1)],
            [ActivityCode::fromString('activity_1'), RequestOrder::fromInt(2)],
        ];

        new Candidate(
            Id::next(),
            Email::fromString('email@test.com'),
            StringValueObject::fromString('Candidate name'),
            StringValueObject::fromString('group name'),
            $requestedActvities
        );
    }
}