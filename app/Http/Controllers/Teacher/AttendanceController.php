<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Show the form for recording attendance for a specific schedule.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $request->validate([
            'training_id' => 'nullable|exists:trainings,training_id',
            'date' => 'nullable|date',
        ]);

        $user = auth()->user();
        $trainings = Training::with('course')
            ->where('teacher_id', $user->user_id)
            ->get();

        $training = null;
        $date = $request->date ?? date('Y-m-d');

        if ($request->filled('training_id')) {
            $training = $trainings->firstWhere('training_id', $request->training_id);

            if (!$training) {
                abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
            }

            $training->load(['enrollments.student.person']);
        }

        return view('teacher.attendance', compact('trainings', 'training', 'date'));
    }

    /**
     * Store attendance records for a specific training and date.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate training_id, date and attendance array
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:users,user_id',
            'attendances.*.status' => 'required|in:P,A,J',
        ]);

        // Buscamos la capacitación limpia para validar propiedad
        $training = \App\Models\Training::findOrFail($request->training_id);

        if ($training->teacher_id !== auth()->user()->user_id) {
            abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
        }

        // Buscamos el schedule_id directo en la tabla
        $scheduleId = DB::table('schedules')
            ->where('training_id', $training->training_id)
            ->value('schedule_id') ?? 1;

        // Use transaction for atomic operations
        DB::transaction(function () use ($request, $scheduleId, $training) {
            foreach ($request->attendances as $attendanceData) {
                
                // Extraemos el enrollment_id del estudiante en esta capacitación
                $enrollmentId = DB::table('enrollments')
                    ->where('training_id', $training->training_id)
                    ->where('student_id', $attendanceData['student_id'])
                    ->value('enrollment_id');

                // Mapeo exacto según los valores definidos en el ENUM de la base de datos
                $statusValue = match ($attendanceData['status']) {
                    'P' => 'present',
                    'A' => 'absent',
                    'J' => 'late',   // Mapeamos Justificado como 'late' (tarde) para cumplir con el enum
                    default => 'absent',
                };

                // Guardado limpio sin problemas de truncado de datos
                Attendance::updateOrCreate(
                    [
                        'schedule_id'   => $scheduleId,
                        'enrollment_id' => $enrollmentId ?? $attendanceData['student_id'],
                    ],
                    [
                        'attendance'    => $statusValue, 
                    ]
                );
            }
        });

        return redirect()
            ->route('teacher.courses')
            ->with('success', 'Asistencias guardadas correctamente.');
    }
}