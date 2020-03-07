<?php

namespace App\Tests\UseCase\Context;

use App\Application\Command\RequestActivitiesCommandBuilder;
use App\Application\RequestActivitiesCommandHandler;
use App\Domain\CandidateRepository;
use App\Domain\RequestedActivty;
use App\Domain\ValueObject\Email;
use App\Kernel;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

class LoadCandidateRequestsContext implements Context
{
    /**
     * @var array
     */
    private $requests = [];
    private $requestActivitiesCommandHandler;
    private $candidateRepository;
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * LoadCandidateRequestsContext constructor.
     * @param $requestActivitiesCommandHandler
     * @param $candidateRepository
     */
    public function __construct(Kernel $kernel, CandidateRepository $repository, RequestActivitiesCommandHandler $commandHandler)
    {
        $this->candidateRepository = $repository;
        $this->requestActivitiesCommandHandler = $commandHandler;
        $this->kernel = $kernel;
    }

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
     * @Given /^a list with the following requests per user$/
     */
    public function aListWithTheFollowingRequestsPerUser(TableNode $table)
    {
        foreach ($table as $node) {
            $builder = new RequestActivitiesCommandBuilder();
            $builder->withEmail($node['email'])
                ->withCandidateName($node['candidate'])
                ->withGroup($node['group'])
                ->withOption($node['option1'])
                ->withOption($node['option2'])
                ->withOption($node['option3'])
                ->withOption($node['option4'])
                ->withOption($node['option5']);

            $this->requests[] = $builder->build();
        }
    }

    /**
     * @When /^requests are loaded$/
     */
    public function requestsAreLoaded()
    {
        foreach ($this->requests as $request) {
            $this->requestActivitiesCommandHandler->__invoke($request);
        }
    }

    /**
     * @Then /^candidate of email "([^"]*)" has been registered with "([^"]*)" ordered requests$/
     */
    public function candidateOfEmailHasBeenRegisteredWithOrderedRequests($email, $requestedActivitiesCodes)
    {
        $candidate = $this->candidateRepository->findByEmail(Email::fromString($email));

        Assert::notNull($candidate);

        Assert::eq($requestedActivitiesCodes, implode(',', array_map(
                function (RequestedActivty $requestedActivty) {
                    return (string)$requestedActivty->code();
                },
                $candidate->requestedActivities()->toArray()
            ))
        );
    }

}