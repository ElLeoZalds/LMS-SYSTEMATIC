<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Tests\TestCase as BaseTestCase;

class CertificatesReportsConsistencyTest extends BaseTestCase
{
    public function test_enrollment_certificate_eligibility_uses_a_single_threshold(): void
    {
        $eligibleEnrollment = new class extends Enrollment {
            public function calculateAverage()
            {
                return 13.0;
            }
        };

        $nonEligibleEnrollment = new class extends Enrollment {
            public function calculateAverage()
            {
                return 12.9;
            }
        };

        $this->assertTrue($eligibleEnrollment->canReceiveCertificate(13.0));
        $this->assertFalse($nonEligibleEnrollment->canReceiveCertificate(13.0));
    }

    public function test_assessment_average_uses_only_submitted_attempts(): void
    {
        $assessment = new Assessment();
        $assessment->setRelation('attempts', collect([
            new \App\Models\AssessmentAttempt(['submitted_at' => Carbon::now(), 'score' => 10]),
            new \App\Models\AssessmentAttempt(['submitted_at' => null, 'score' => 20]),
            new \App\Models\AssessmentAttempt(['submitted_at' => Carbon::now(), 'score' => 14]),
        ]));

        $this->assertSame(12.0, $assessment->averageSubmittedScore());
    }
}
