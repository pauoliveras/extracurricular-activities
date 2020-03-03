<?php

namespace App\Application\Command;

class RequestActivitiesCommand
{
    private $email;
    private $candidateName;
    private $group;
    private $orderedOtions = [];

    public function __construct(string $email, string $candidateName, string $group, array $orderedOtions)
    {
        $this->email = $email;
        $this->candidateName = $candidateName;
        $this->group = $group;
        $this->orderedOtions = $orderedOtions;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function candidateName(): string
    {
        return $this->candidateName;
    }

    public function group(): string
    {
        return $this->group;
    }

    public function orderedOtions(): array
    {
        return $this->orderedOtions;
    }
}