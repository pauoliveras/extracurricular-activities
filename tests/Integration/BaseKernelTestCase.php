<?php

namespace App\Tests\Integration;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseKernelTestCase extends KernelTestCase
{
    protected $testContainer;

    protected function setUp(): void
    {
        parent::setup();
        static::bootKernel();
        $this->testContainer = static::$container->get('test.private_services_locator');
        $this->purgeDatabase();
    }

    private function purgeDatabase(string ...$tables)
    {
        $em = $this->testContainer->get(EntityManagerInterface::class);
        $purger = new ORMPurger($em, $tables);
        $purger->purge();
    }
}