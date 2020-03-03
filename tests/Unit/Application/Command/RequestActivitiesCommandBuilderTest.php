<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use App\Application\Command\RequestActivitiesCommandBuilder;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandBuilderTest extends TestCase
{
    public function test_command_can_be_created_with_a_request_list()
    {
        $command = (new RequestActivitiesCommandBuilder())->withEmail('test@email.com')
            ->withCandidateName('candidate name')
            ->withGroup('group')
            ->withOption('option1')
            ->withOption('option2')
            ->withOption('option3')
            ->build();

        $this->assertEquals('test@email.com', $command->email());
        $this->assertEquals('candidate name', $command->candidateName());
        $this->assertEquals('group', $command->group());
        $this->assertEquals(['option1', 'option2', 'option3'], $command->orderedOtions());
    }
}