<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Training;

class CourseController extends Controller
{
    public function show($id)
    {
        $studentId = auth()->id();

        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('training_id', $id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'No estás inscrito en esta capacitación.');
        }

        $training = Training::with(['course', 'teacher.person', 'assessments'])
            ->where('training_id', $id)
            ->firstOrFail();

        return view('student.courses.show', compact('training'));
    }
}
