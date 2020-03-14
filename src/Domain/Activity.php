<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Activity
 * @package App\Domain
 * @ORM\Entity
 */
class Activity
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $code;

    public function __construct(Id $id, ActivityCode $code)
    {
        $this->id = $id;
        $this->code = $code->value();
    }

    public function isNull(): bool
    {
        return false;
    }
}