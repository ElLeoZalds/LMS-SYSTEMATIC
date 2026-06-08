<?php

namespace Tests\Unit;

use App\Models\Schedule;
use App\Models\Training;
use Carbon\Carbon;
use Tests\TestCase;

class TrainingDateStatusTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_training_is_closed_after_last_schedule_date(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 6, 15));

        $training = new Training(['status' => 'A']);
        $training->setRelation('schedules', collect([
            new Schedule(['date' => '2026-06-01']),
            new Schedule(['date' => '2026-06-10']),
        ]));

        $this->assertTrue($training->isClosed());
        $this->assertFalse($training->isActive());
    }

    public function test_training_is_active_while_inside_schedule_dates(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 6, 5));

        $training = new Training(['status' => 'A']);
        $training->setRelation('schedules', collect([
            new Schedule(['date' => '2026-06-01']),
            new Schedule(['date' => '2026-06-10']),
        ]));

        $this->assertTrue($training->isActive());
        $this->assertFalse($training->isClosed());
    }
}
