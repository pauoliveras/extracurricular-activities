<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Domain\CandidateRepository;
use App\Domain\NullCandidate;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCandidateRepository implements CandidateRepository
{
    private $repository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Candidate::class);
        $this->entityManager = $entityManager;
    }

    public function save(Candidate $candidate)
    {
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();
    }

    public function findByEmail(Email $email): Candidate
    {
        $candidate = $this->repository->findOneBy(['email' => $email]);

        return $candidate ?? NullCandidate::create();
    }

    public function nextId(): Id
    {
        return Id::next();
    }

    public function findAll(): CandidateCollection
    {
        return new CandidateCollection($this->repository->findAll());
    }
}