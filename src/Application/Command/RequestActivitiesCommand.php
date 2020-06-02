<?php

namespace App\Application\Command;

class RequestActivitiesCommand
{
    private string $email;
    private string $candidateName;
    private string $group;
    private array $orderedOtions = [];
    private string $candidateCode;

    public function __construct(string $candidateCode, string $email, string $candidateName, string $group, array $orderedOtions)
    {
        $this->candidateCode = $candidateCode;
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

    public function candidateCode(): string
    {
        return $this->candidateCode;
    }
}