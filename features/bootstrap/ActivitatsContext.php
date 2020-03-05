<?php

use App\Application\Command\RequestActivitiesCommand;
use App\Application\Command\RequestActivitiesCommandBuilder;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ActivitatsContext implements Context
{
    /**
     * @var array
     */
    private $requests = [];
    private $requestActivitiesCommandHandler;
    private $candidateRepository;

    /**
     * ActivitatsContext constructor.
     * @param $requestActivitiesCommandHandler
     * @param $candidateRepository
     */
    public function __construct($requestActivitiesCommandHandler, $candidateRepository)
    {
        $this->requestActivitiesCommandHandler = $requestActivitiesCommandHandler;
        $this->candidateRepository = $candidateRepository;
    }


    /**
     * @Given /^a list with the following requests per user$/
     */
    public function aListWithTheFollowingRequestsPerUser(\Behat\Gherkin\Node\TableNode $table)
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
     * @Then /^email of email "([^"]*)" ordered requested options are "([^"]*)"$/
     */
    public function emailOfEmailOrderedRequestedOptionsAre($email, $orderedRequestedActivities)
    {
        $candidate = $this->candidateRepository->findByEmail(EmailValueObject::fromString($email));

        Assert::eq($orderedRequestedActivities, $candidate->orderedRequests());
    }


}