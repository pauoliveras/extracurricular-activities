<?php

use App\Application\Command\RequestActivitiesCommand;
use App\Application\Command\RequestActivitiesCommandBuilder;
use Behat\Behat\Context\Context;

class ActivitatsContext implements Context
{
    /**
     * @var array
     */
    private $requests = [];

    /**
     * @When /^requests are loaded$/
     */
    public function requestsAreLoaded()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Then /^email of email "([^"]*)" ordered requested options are "([^"]*)"$/
     */
    public function emailOfEmailOrderedRequestedOptionsAre($email, $orderedRequestedActivities)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
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


}