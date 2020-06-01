<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Activity;
use App\Domain\ActivityRepository;
use App\Domain\NullActivity;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineActivityRepository implements ActivityRepository
{

    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Activity::Class);
    }

    public function findByCode(ActivityCode $code): Activity
    {
        $activity = $this->repository->findOneBy(['code' => $code->value()]);

        return $activity ?? NullActivity::createNull();
    }

    public function ofId(Id $id): Activity
    {
        return $this->repository->find($id);
    }

    public function save(Activity $activity)
    {
        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }
}