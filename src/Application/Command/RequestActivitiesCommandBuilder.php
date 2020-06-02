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
    private ?string $code = null;

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

    public function withOption(string $option): self
    {
        if (!empty($option)) {
            $this->orderedOtions[] = $option;
        }

        return $this;
    }

    public function build(): RequestActivitiesCommand
    {
        return new RequestActivitiesCommand($this->code ?? $this->defaultCode(), $this->email, $this->candidateName, $this->group, $this->orderedOtions);
    }

    private function defaultCode()
    {
        return sprintf('%s|%s', $this->candidateName, $this->group);
    }
}