<?php

namespace Tests\Unit;

use App\Models\Content;
use App\Models\Enrollment;
use App\Models\Training;
use Carbon\Carbon;
use Tests\TestCase as BaseTestCase;

class ContentProgressConsistencyTest extends BaseTestCase
{
    public function test_content_is_available_only_when_training_is_active_and_open(): void
    {
        $training = new Training([
            'status' => Training::STATUS_ACTIVE,
            'start_date' => Carbon::today()->subDay(),
            'end_date' => Carbon::today()->addDay(),
        ]);

        $content = new Content();
        $content->setRelation('training', $training);

        $this->assertTrue($content->canBeAccessed());

        $training->end_date = Carbon::today()->subDay()->toDateString();

        $this->assertFalse($content->canBeAccessed());
    }

    public function test_completed_contents_count_only_counts_completed_progress_for_training_contents(): void
    {
        $training = new Training(['training_id' => 1]);
        $training->setRelation('contents', collect([
            (object) ['content_id' => 1],
            (object) ['content_id' => 2],
        ]));

        $enrollment = new Enrollment(['enrollment_id' => 7]);
        $enrollment->setRelation('training', $training);
        $enrollment->setRelation('progress', collect([
            (object) ['content_id' => 1, 'percentage' => 100, 'status' => 'C'],
            (object) ['content_id' => 2, 'percentage' => 40, 'status' => 'A'],
            (object) ['content_id' => 9, 'percentage' => 100, 'status' => 'C'],
        ]));

        $this->assertSame(1, $enrollment->completedContentsCount());
    }
}
