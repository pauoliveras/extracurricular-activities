<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\SequenceNumber;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Candidate
 * @package App\Domain
 * @ORM\Entity()
 */
class WaitingCandidate
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private string $id;
    /**
     * @ORM\Column(type="string")
     * @ORM\Id()
     */
    private string $activityCode;
    /**
     * @ORM\Column(type="integer")
     */
    private int $candidateNumber;
    /**
     * @ORM\Column(type="integer")
     */
    private int $sequenceNumber;

    public function __construct(
        Id $id,
        ActivityCode $activityCode,
        CandidateNumber $candidateNumber,
    SequenceNumber $sequenceNumber
    )
    {

        $this->id = $id;
        $this->activityCode = $activityCode->value();
        $this->candidateNumber = $candidateNumber->value();
        $this->sequenceNumber = $sequenceNumber->value();
    }

    public static function register(Id $candidateId, ValueObject\ActivityCode $activityCode, CandidateNumber $candidateNumber, SequenceNumber $sequenceNumber)
    {
        return new self($candidateId, $activityCode, $candidateNumber, $sequenceNumber);
    }

}