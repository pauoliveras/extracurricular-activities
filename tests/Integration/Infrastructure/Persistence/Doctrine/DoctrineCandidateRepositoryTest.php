<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine;

use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use App\Tests\Integration\BaseKernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCandidateRepositoryTest extends BaseKernelTestCase
{
    private $repository;
    private $em;

    public function test_candidate_can_be_saved_and_retrieved()
    {
        $candidate = new Candidate(
            Id::next(),
            Email::fromString('test@email.com'),
            StringValueObject::fromString('candidate name'),
            StringValueObject::fromString('group'),
            [
                [ActivityCode::fromString('activity_1'), RequestOrder::fromInt(1)],
                [ActivityCode::fromString('activity_2'), RequestOrder::fromInt(2)],
                [ActivityCode::fromString('activity_3'), RequestOrder::fromInt(3)]
            ]
        );

        $this->repository->save($candidate);

        $this->em->clear();

        $savedCandidate = $this->repository->findByEmail(Email::fromString('test@email.com'));

        $this->assertEquals($candidate->email(), $savedCandidate->email());
        $this->assertEquals($candidate->candidateName(), $savedCandidate->candidateName());
        $this->assertEquals($candidate->candidateGroup(), $savedCandidate->candidateGroup());
        $this->assertEquals($candidate->requestedActivities(), $savedCandidate->requestedActivities());
    }

    protected function setUp(): void
    {
        parent::setup();

        $this->repository = $this->testContainer->get(CandidateRepository::class);

        $this->em = $this->testContainer->get(EntityManagerInterface::class);
    }

}