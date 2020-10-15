<?php

namespace App\Domain\Read;

use App\Domain\ValueObject\Id;

interface ParticipantDesiredAssignmentsRepository
{
    public function findByCandidateId(Id $candidateId): ParticipantDesiredAssignments;
}