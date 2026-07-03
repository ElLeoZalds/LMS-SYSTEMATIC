<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Models\Training;
use PHPUnit\Framework\TestCase;

class AcademicCoreConsistencyTest extends TestCase
{
    public function test_training_status_constants_are_consistent(): void
    {
        $training = new Training(['status' => Training::STATUS_ACTIVE]);

        $this->assertSame(Training::STATUS_ACTIVE, $training->normalizedStatus());
        $this->assertTrue($training->isActive());
        $this->assertFalse($training->isClosed());
    }

    public function test_enrollment_status_constants_are_consistent(): void
    {
        $activeEnrollment = new Enrollment(['status' => Enrollment::STATUS_ACTIVE]);
        $completedEnrollment = new Enrollment(['status' => Enrollment::STATUS_COMPLETED]);

        $this->assertTrue($activeEnrollment->isInProgress());
        $this->assertTrue($completedEnrollment->isCompleted());
        $this->assertFalse($activeEnrollment->isCompleted());
    }
}
