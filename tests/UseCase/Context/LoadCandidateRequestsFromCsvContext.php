<?php

namespace App\Tests\UseCase\Context;

use App\Domain\Activity;
use App\Domain\ActivityRepository;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Persistence\Doctrine\DoctrineActivityRepository;
use App\Kernel;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
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
     * @var DoctrineActivityRepository
     */
    private $activityRepository;

    /**
     * @BeforeScenario
     */
    public function cleanDB(BeforeScenarioScope $scope)
    {
        $testContainer = $this->kernel->getContainer()->get('test.service_container');

        $em = $testContainer->get(EntityManagerInterface::class);
        $purger = new ORMPurger($em, []);

        $purger->purge();

        $this->activityRepository = $testContainer->get(ActivityRepository::class);
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

    /**
     * @Given /^following activities are available to request:$/
     */
    public function followingActivitiesAreAvailableToRequest(TableNode $table)
    {
        foreach ($table as $activity) {
            $this->activityRepository->save(new Activity(Id::next(), ActivityCode::fromString($activity['activity_code'])));
        }
    }

}