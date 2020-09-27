<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RequestActivitiesCommand;
use PHPUnit\Framework\TestCase;

class RequestActivitiesCommandTest extends TestCase
{
    public function test_command_can_be_created_with_a_request_list()
    {
        $command = new RequestActivitiesCommand(
            'code',
            'test@email.com',
            'candidate name',
            'group',
            ['option1', 'option2', 'option3'],
            5,
            true
        );

        $this->assertEquals('test@email.com', $command->email());
        $this->assertEquals('candidate name', $command->candidateName());
        $this->assertEquals('group', $command->group());
        $this->assertEquals(['option1', 'option2', 'option3'], $command->orderedOtions());
    }
}