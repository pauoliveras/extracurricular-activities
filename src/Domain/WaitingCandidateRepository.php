<?php

namespace App\Domain;

use App\Domain\ValueObject\Id;

interface WaitingCandidateRepository
{
    public function save(WaitingCandidate $candidate);

    public function findByCandidateId(Id $id): ?WaitingCandidate;

}