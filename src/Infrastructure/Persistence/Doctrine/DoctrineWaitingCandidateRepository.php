<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\ValueObject\Id;
use App\Domain\WaitingCandidate;
use App\Domain\WaitingCandidateRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineWaitingCandidateRepository implements WaitingCandidateRepository
{
    private $repository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(WaitingCandidate::class);
        $this->entityManager = $entityManager;
    }

    public function save(WaitingCandidate $candidate)
    {
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();
    }

    public function findByCandidateId(Id $id): ?WaitingCandidate
    {
        return $this->repository->find($id);
    }
}