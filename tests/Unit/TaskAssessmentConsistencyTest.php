<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\TaskSubmission;
use Carbon\Carbon;
use Tests\TestCase as BaseTestCase;

class TaskAssessmentConsistencyTest extends BaseTestCase
{
    public function test_assessment_availability_is_consistent_for_active_windows(): void
    {
        $assessment = new Assessment([
            'active' => true,
            'start_date' => Carbon::today()->subDay(),
            'end_date' => Carbon::today()->addDay(),
        ]);

        $this->assertTrue($assessment->isAvailableOnDate(Carbon::today()));

        $assessment->end_date = Carbon::today()->subDay()->toDateString();
        $this->assertFalse($assessment->isAvailableOnDate(Carbon::today()));
    }

    public function test_task_submission_and_attempt_helpers_are_consistent(): void
    {
        $submission = new TaskSubmission(['submitted_at' => Carbon::now()]);
        $attempt = new AssessmentAttempt(['submitted_at' => Carbon::now(), 'score' => 15]);

        $this->assertTrue($submission->isSubmitted());
        $this->assertTrue($attempt->isSubmitted());
        $this->assertTrue($attempt->isPassed());
    }
}
