<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine;

use App\Domain\Activity;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Persistence\Doctrine\DoctrineActivityRepository;
use App\Tests\Integration\BaseKernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineActivityRepositoryTest extends BaseKernelTestCase
{
    private $repository;
    private $em;

    public function test_activities_can_be_saved_and_retrieved()
    {
        $activityId = Id::next();
        $activity = new Activity(
            $activityId,
            ActivityCode::fromString('activity')
        );

        $this->repository->save($activity);

        $this->em->clear();

        $savedActivity = $this->repository->ofId($activityId);

        $this->assertEquals($activity, $savedActivity);
    }

    protected function setUp(): void
    {
        parent::setup();

        $this->repository = $this->testContainer->get(DoctrineActivityRepository::class);
        $this->em = $this->testContainer->get(EntityManagerInterface::class);
    }

}