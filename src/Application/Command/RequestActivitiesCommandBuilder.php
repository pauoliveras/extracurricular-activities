<?php

namespace App\Application\Command;

class RequestActivitiesCommandBuilder
{
    private $email;
    private $candidateName;
    private $group;
    private array $orderedOtions = [];
    private ?int $desiredActivityCount = null;
    private ?string $code = null;
    private ?bool $membership = false;

    public function __construct()
    {
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function withCandidateName(string $candidateName): self
    {
        $this->candidateName = $candidateName;

        return $this;
    }

    public function withGroup(string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function withCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function withDesiredActivityCount(?int $desiredActivityCount): self
    {
        $this->desiredActivityCount = $desiredActivityCount;

        return $this;
    }

    public function withOption(string $option): self
    {
        if (!empty($option)) {
            $this->orderedOtions[] = $option;
        }

        return $this;
    }

    public function build(): RequestActivitiesCommand
    {
        return new RequestActivitiesCommand(
            $this->code ?? $this->defaultCode(),
            $this->email,
            $this->candidateName,
            $this->group,
            $this->orderedOtions,
            $this->desiredActivityCount,
            $this->membership
        );
    }

    private function defaultCode()
    {
        return sprintf('%s|%s', $this->candidateName, $this->group);
    }

    public function withMembership(?bool $isMember = false)
    {
        $this->membership = $isMember;

        return $this;
    }
}