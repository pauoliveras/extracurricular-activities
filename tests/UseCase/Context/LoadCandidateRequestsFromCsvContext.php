<?php

namespace App\Tests\UseCase\Context;

use App\Domain\Activity;
use App\Domain\ActivityRepository;
use App\Domain\CandidateRepository;
use App\Domain\RequestedActivity;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Id;
use App\Tests\Infrastructure\Stubs\StubCapacity;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class LoadCandidateRequestsFromCsvContext extends BaseContext
{
    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var CandidateRepository
     */
    private $candidateRepository;
    private int $commandResult;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->activityRepository = $this->testContainer->get(ActivityRepository::class);
        $this->candidateRepository = $this->testContainer->get(CandidateRepository::class);
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
        $this->commandResult = $commandTester->execute(['filename' => $filename]);
    }

    /**
     * @Given /^following activities are available to request:$/
     */
    public function followingActivitiesAreAvailableToRequest(TableNode $table)
    {
        foreach ($table as $activity) {
            $this->activityRepository->save(
                new Activity(
                    Id::next(),
                    ActivityCode::fromString($activity['activity_code']),
                    StubCapacity::random()
                )
            );
        }
    }

    /**
     * @Then /^candidate "([^"]*)" has been registered with "([^"]*)" ordered requests$/
     */
    public function candidateHasBeenRegisteredWithOrderedRequests($candidateCode, $requestedActivitiesCodes)
    {
        $candidate = $this->candidateRepository->findByCode(CandidateCode::fromString($candidateCode));

        Assert::notNull($candidate);

        Assert::eq($requestedActivitiesCodes, implode(',', array_map(
                function (RequestedActivity $requestedActivty) {
                    return (string)$requestedActivty->code();
                },
                $candidate->requestedActivities()->toArray()
            ))
        );
    }

    /**
     * @Given candidate :candidateCode wants :candidateMaxActivities activities at most
     */
    public function candidateWantsActivitiesAtMost($candidateCode, $candidateMaxActivities)
    {
        $candidate = $this->candidateRepository->findByCode(CandidateCode::fromString($candidateCode));

        Assert::notNull($candidate);

        Assert::eq($candidate->desiredActivityCount()->value(), (int)$candidateMaxActivities);
    }

    /**
     * @Given candidate :candidateCode wants all requested activities
     */
    public function candidateWantsAllRequestedActivities($candidateCode)
    {
        $candidate = $this->candidateRepository->findByCode(CandidateCode::fromString($candidateCode));

        Assert::notNull($candidate);

        Assert::true($candidate->desiredActivityCount()->equalsTo(DesiredActivityCount::fromInt(null)));
    }

    /**
     * @Then /^command execution is successfull$/
     */
    public function commandExecutionIsSuccessfull()
    {
        Assert::eq($this->commandResult, 0, 'Command execution result different from expected!');
    }

}