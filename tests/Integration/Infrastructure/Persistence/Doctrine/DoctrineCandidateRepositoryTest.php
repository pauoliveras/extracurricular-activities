<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine;

use App\Domain\Candidate;
use App\Domain\CandidateRepository;
use App\Domain\RequestedActivitiesList;
use App\Domain\ValueObject\BooleanValueObject;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\StringValueObject;
use App\Infrastructure\Persistence\Doctrine\DoctrineCandidateRepository;
use App\Tests\Integration\BaseKernelTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCandidateRepositoryTest extends BaseKernelTestCase
{
    private DoctrineCandidateRepository $repository;
    private EntityManager $em;

    public function test_candidate_can_be_saved_and_retrieved()
    {
        $candidate = new Candidate(
            Id::next(),
            CandidateCode::fromString('code'),
            Email::fromString('test@email.com'),
            StringValueObject::fromString('candidate name'),
            StringValueObject::fromString('group'),
            RequestedActivitiesList::createFromArray(['activity_1', 'activity_2', 'activity_3']),
            DesiredActivityCount::fromInt(4),
            BooleanValueObject::true()
        );

        $this->repository->save($candidate);

        $this->em->clear();

        $savedCandidate = $this->repository->findByCode(CandidateCode::fromString('code'));

        $this->assertEquals($candidate->email(), $savedCandidate->email());
        $this->assertEquals($candidate->candidateName(), $savedCandidate->candidateName());
        $this->assertEquals($candidate->candidateCode(), $savedCandidate->candidateCode());
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