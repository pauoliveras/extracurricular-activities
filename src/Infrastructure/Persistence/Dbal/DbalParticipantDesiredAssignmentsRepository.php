<?php

namespace App\Infrastructure\Persistence\Dbal;

use App\Domain\Read\ParticipantDesiredAssignments;
use App\Domain\Read\ParticipantDesiredAssignmentsRepository;
use App\Domain\ValueObject\DesiredActivityCount;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\IntValueObject;
use Doctrine\DBAL\Connection;

class DbalParticipantDesiredAssignmentsRepository implements ParticipantDesiredAssignmentsRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findByCandidateId(Id $candidateId): ParticipantDesiredAssignments
    {
        $result = $this->connection->executeQuery(
            'SELECT c.candidate_number, c.desired_activity_count, count(p.activity_id) as activity_count
                    FROM candidate c LEFT JOIN participant p on c.id = p.candidate_id 
                    WHERE c.id = :candidate_id
                    GROUP BY c.candidate_number, c.desired_activity_count',
            ['candidate_id' => $candidateId->value()]
        )->fetch();

        return new ParticipantDesiredAssignments(
            $candidateId,
            DesiredActivityCount::fromInt($result['desired_activity_count']),
            IntValueObject::fromInt($result['activity_count'] ?? 0)
        );
    }
}