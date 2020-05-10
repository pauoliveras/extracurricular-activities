<?php

namespace App\Tests\UseCase\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext implements Context
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    protected $testContainer;

    /**
     * BaseKernelAwareContext constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->testContainer = $kernel->getContainer()->get('test.service_container');
    }

    /**
     * @BeforeScenario
     */
    public function cleanDB(BeforeScenarioScope $scope)
    {
        $em = $this->testContainer->get(EntityManagerInterface::class);
        $purger = new ORMPurger($em, []);

        $purger->purge();
    }
}