<?php

namespace App\Application\Command;

class RequestActivitiesCommandBuilder
{
    private $email;
    private $candidateName;
    private $group;
    /**
     * @var array
     */
    private $orderedOtions;

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

    public function withOption(string $option): self
    {
        if (!empty($option)) {
            $this->orderedOtions[] = $option;
        }

        return $this;
    }

    public function build(): RequestActivitiesCommand
    {
        return new RequestActivitiesCommand($this->email, $this->candidateName, $this->group, $this->orderedOtions);
    }
}