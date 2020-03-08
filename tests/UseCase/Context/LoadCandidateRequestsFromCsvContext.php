<?php

namespace App\Tests\UseCase\Context;

use App\Kernel;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class LoadCandidateRequestsFromCsvContext implements KernelAwareContext
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @BeforeScenario
     */
    public function cleanDB(BeforeScenarioScope $scope)
    {
        $testContainer = $this->kernel->getContainer()->get('test.service_container');

        $em = $testContainer->get(EntityManagerInterface::class);
        $purger = new ORMPurger($em, []);

        $purger->purge();
    }

    /**
     * @inheritDoc
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^a file named "([^"]*)" with the following content$/
     */
    public function aFileNamedWithTheFollowingContent($filename, PyStringNode $fileContent)
    {
        $filesystem = new Filesystem();
        $filename = sprintf('%s/%s', $this->kernel->getProjectDir(), $filename);
        $filesystem->remove($filename);
        $filesystem->dumpFile($filename, $fileContent);
    }

    /**
     * @When /^load requests command is executed against file "([^"]*)"$/
     */
    public function loadRequestsCommandIsExecutedWithFilenameParameter(string $filename)
    {
        $application = new Application($this->kernel);

        $command = $application->find('app:load-requests');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filename' => $filename,
        ]);
    }

}