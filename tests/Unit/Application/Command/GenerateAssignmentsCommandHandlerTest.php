<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\AssignCandidateToActivityCommandHandler;
use App\Application\Command\GenerateAssignmentsCommand;
use App\Application\GenerateAssignmentsCommandHandler;
use App\Application\GetCandidatesOrderedByNumberQueryHandler;
use App\Domain\Candidate;
use App\Domain\Participant;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\LuckyDrawNumber;
use App\Tests\Infrastructure\Stubs\ActivityStubBuilder;
use App\Tests\Infrastructure\Stubs\CandidateStubBuilder;
use App\Tests\Infrastructure\Stubs\InMemoryActivityRepository;
use App\Tests\Infrastructure\Stubs\InMemoryCandidateRepository;
use App\Tests\Infrastructure\Stubs\StubActivityCode;
use PHPUnit\Framework\TestCase;

class GenerateAssignmentsCommandHandlerTest extends TestCase
{
    private $assignmentGenerator;
    private $candidateRepository;
    private $activityRepository;

    public function test_a_candidate_is_assigned_to_a_requested_activity()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga']);
        $activity = $this->givenAnActivityWithCodeAndCapacity('ioga', 1);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(1)->value()));

        $this->assertEquals([1], $this->extractParticipantNumbers($activity->participants()->toArray()));
    }

    protected function givenACandidateWithNumberAndActivities(int $number, array $activities): Candidate
    {
        $candidate = CandidateStubBuilder::create()
            ->withNumber(CandidateNumber::fromInt($number))
            ->withActivities($activities)->build();
        $this->candidateRepository->save($candidate);
        return $candidate;
    }

    private function givenAnActivityWithCodeAndCapacity(string $code, int $capacity)
    {
        $activity = ActivityStubBuilder::create()
            ->withCapacity(Capacity::fromInt($capacity))
            ->withCode(StubActivityCode::create($code))
            ->build();
        $this->activityRepository->save($activity);

        return $activity;
    }

    private function extractParticipantNumbers(array $participants)
    {
        return array_map(function (Participant $participant) {
            return $participant->number()->value();
        }, $participants);
    }

    public function test_two_candidates_are_assigned_to_a_requested_activity()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga']);
        $this->givenACandidateWithNumberAndActivities(2, ['ioga']);

        $activity = $this->givenAnActivityWithCodeAndCapacity('ioga', 2);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(1)->value()));

        $this->assertEquals([1, 2], $this->extractParticipantNumbers($activity->participants()->toArray()));
    }

    public function test_candidate_requests_are_taken_in_order_when_preceding_not_available()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga', 'piscina']);
        $this->givenACandidateWithNumberAndActivities(2, ['ioga', 'piscina']);

        $activityIoga = $this->givenAnActivityWithCodeAndCapacity('ioga', 1);
        $activityPiscina = $this->givenAnActivityWithCodeAndCapacity('piscina', 1);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(1)->value()));

        $this->assertEquals([1], $this->extractParticipantNumbers($activityIoga->participants()->toArray()));
        $this->assertEquals([2], $this->extractParticipantNumbers($activityPiscina->participants()->toArray()));
    }

    public function test_all_candidate_requests_are_processed()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga', 'piscina']);
        $this->givenACandidateWithNumberAndActivities(2, ['ioga', 'piscina']);

        $activityIoga = $this->givenAnActivityWithCodeAndCapacity('ioga', 2);
        $activityPiscina = $this->givenAnActivityWithCodeAndCapacity('piscina', 2);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(1)->value()));

        $this->assertEquals([1, 2], $this->extractParticipantNumbers($activityIoga->participants()->toArray()));
        $this->assertEquals([1, 2], $this->extractParticipantNumbers($activityPiscina->participants()->toArray()));
    }

    public function test_all_candidate_requests_are_processed_according_to_capacity()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga', 'piscina']);
        $this->givenACandidateWithNumberAndActivities(2, ['ioga', 'piscina']);

        $activityIoga = $this->givenAnActivityWithCodeAndCapacity('ioga', 1);
        $activityPiscina = $this->givenAnActivityWithCodeAndCapacity('piscina', 2);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(1)->value()));

        $this->assertEquals([1], $this->extractParticipantNumbers($activityIoga->participants()->toArray()));
        $this->assertEquals([2, 1], $this->extractParticipantNumbers($activityPiscina->participants()->toArray()));
    }

    public function test_candidates_are_assigned_to_requested_activity_based_on_lucky_draw_number()
    {
        $this->givenACandidateWithNumberAndActivities(1, ['ioga', 'piscina']);
        $this->givenACandidateWithNumberAndActivities(2, ['ioga', 'piscina']);

        $activityIoga = $this->givenAnActivityWithCodeAndCapacity('ioga', 2);
        $activityPiscina = $this->givenAnActivityWithCodeAndCapacity('piscina', 2);

        $this->assignmentGenerator->__invoke(new GenerateAssignmentsCommand(LuckyDrawNumber::fromInt(2)->value()));

        $this->assertEquals([2, 1], $this->extractParticipantNumbers($activityIoga->participants()->toArray()));
        $this->assertEquals([2, 1], $this->extractParticipantNumbers($activityPiscina->participants()->toArray()));
    }

    protected function setUp(): void
    {
        $this->candidateRepository = new InMemoryCandidateRepository();
        $this->activityRepository = new InMemoryActivityRepository();

        $this->assignmentGenerator = new GenerateAssignmentsCommandHandler(
            new GetCandidatesOrderedByNumberQueryHandler($this->candidateRepository),
            new AssignCandidateToActivityCommandHandler($this->activityRepository)
        );
    }

}