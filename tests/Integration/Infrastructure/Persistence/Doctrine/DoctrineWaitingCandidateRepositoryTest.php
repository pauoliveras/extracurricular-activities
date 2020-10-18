<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Domain\WaitingCandidate;
use App\Domain\WaitingCandidateRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineWaitingCandidateRepository;
use App\Tests\Integration\BaseKernelTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineWaitingCandidateRepositoryTest extends BaseKernelTestCase
{
    private DoctrineWaitingCandidateRepository $repository;
    private EntityManager $em;

    public function test_waiting_candidate_can_be_saved_and_retrieved()
    {
        $candidate = WaitingCandidate::register(
            $id = Id::next(),
            ActivityCode::fromString('activity_1')
        );

        $candidate->addActivity(ActivityCode::fromString('activity_2'));
        $candidate->addActivity(ActivityCode::fromString('activity_3'));

        $this->repository->save($candidate);

        $this->em->clear();

        $savedCandidate = $this->repository->findByCandidateId($id);

        $this->assertEquals($candidate, $savedCandidate);
    }

    protected function setUp(): void
    {
        parent::setup();

        $this->repository = $this->testContainer->get(WaitingCandidateRepository::class);

        $this->em = $this->testContainer->get(EntityManagerInterface::class);
    }

}