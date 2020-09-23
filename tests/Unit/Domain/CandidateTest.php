<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Candidate;
use App\Domain\RequestedActivitiesList;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\StringValueObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CandidateTest extends TestCase
{
    public function test_a_candidate_can_be_created_with_a_requested_activity()
    {
        $requestedActvities = RequestedActivitiesList::createFromArray(['activity_1']);

        $candidate = new Candidate(
            Id::next(),
            CandidateCode::fromString('Candidate name|group name'),
            Email::fromString('email@test.com'),
            StringValueObject::fromString('Candidate name'),
            StringValueObject::fromString('group name'),
            $requestedActvities,
            DesiredActivityCount::fromInt(3)
        );

        $this->assertEquals('email@test.com', $candidate->email()->value());
        $this->assertEquals('Candidate name', $candidate->candidateName()->value());
        $this->assertEquals('group name', $candidate->candidateGroup()->value());
        $this->assertEquals(3, $candidate->desiredActivityCount()->value());
        $this->assertNotEmpty($candidate->requestedActivities());
    }

    public function test_a_candidate_has_to_be_created_with_at_least_one_request()
    {
        $this->expectException(InvalidArgumentException::class);

        new Candidate(
            Id::next(),
            CandidateCode::fromString('Candidate name|group name'),
            Email::fromString('email@test.com'),
            StringValueObject::fromString('Candidate name'),
            StringValueObject::fromString('group name'),
            RequestedActivitiesList::createFromArray([]),
            DesiredActivityCount::fromInt(3)
        );
    }
}