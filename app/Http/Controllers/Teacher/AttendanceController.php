<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'date' => 'nullable|date',
        ]);

        $user = auth()->user();
        $trainings = Training::with('course')
            ->where('teacher_id', $user->user_id)
            ->get();

        $training = null;
        $date = $request->date ?? date('Y-m-d');

        $selectedScheduleId = null;

        if ($request->filled('training_id')) {
            $training = $trainings->firstWhere('training_id', $request->training_id);

            if (!$training) {
                abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
            }

            // Load enrollments and schedules for the selected training
            $training->load(['enrollments.student.person', 'schedules']);

            // If a specific schedule_id was provided, verify it belongs to the training
            if ($request->filled('schedule_id')) {
                $scheduleId = $request->schedule_id;
                $schedule = DB::table('schedules')
                    ->where('schedule_id', $scheduleId)
                    ->where('training_id', $training->training_id)
                    ->first();

                if ($schedule) {
                    $selectedScheduleId = $schedule->schedule_id;
                    $attendances = Attendance::where('schedule_id', $selectedScheduleId)->with('enrollment')->get();
                    $date = $schedule->date ?? $date;
                } else {
                    $attendances = collect();
                }
            } else {
                $attendances = collect();
            }
        }

        return view('teacher.attendance', compact('trainings', 'training', 'date', 'attendances', 'selectedScheduleId'));
    }

    /**
     * Store attendance records for a specific training and date.
     *
     * @param Request $request
        * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate training_id, date and attendance array
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'schedule_id' => [
                'required',
                Rule::exists('schedules', 'schedule_id')->where(function ($query) use ($request) {
                    if ($request->filled('training_id')) {
                        $query->where('training_id', $request->training_id);
                    }
                }),
            ],
            'date' => 'nullable|date',
            'local_time' => 'nullable|date_format:H:i:s',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:users,user_id',
            'attendances.*.status' => 'required|in:P,A,J,T',
        ]);

        // Buscamos la capacitación limpia para validar propiedad
        $training = \App\Models\Training::findOrFail($request->training_id);

        if ($training->teacher_id !== auth()->user()->user_id) {
            abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
        }

        // Determine schedule: either provided or based on provided date
        $scheduleId = $request->input('schedule_id');

        $schedule = DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->where('training_id', $training->training_id)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'Sesión no válida para esta capacitación.')->withInput();
        }

        $selectedDate = $schedule->date;

        // Ensure schedule date is not in the future
        $scheduleDate = DB::table('schedules')->where('schedule_id', $scheduleId)->value('date');
        if ($scheduleDate && $scheduleDate > date('Y-m-d')) {
            return back()->with('error', 'No se puede tomar asistencia para una fecha futura.')->withInput();
        }

        // Use transaction for atomic operations
        DB::transaction(function () use ($request, $scheduleId, $training) {
            foreach ($request->attendances as $attendanceData) {

                // Extraemos el enrollment_id del estudiante en esta capacitación
                $enrollmentId = DB::table('enrollments')
                    ->where('training_id', $training->training_id)
                    ->where('student_id', $attendanceData['student_id'])
                    ->value('enrollment_id');

                // Mapeo exacto según los códigos enviados desde la UI
                $statusValue = match ($attendanceData['status']) {
                    'P' => 'present',
                    'A' => 'absent',
                    'J' => 'justified',
                    'T' => 'late',
                    default => 'absent',
                };

                // Guardado limpio sin problemas de truncado de datos
                Attendance::updateOrCreate(
                    [
                        'schedule_id'   => $scheduleId,
                        'enrollment_id' => $enrollmentId ?? $attendanceData['student_id'],
                    ],
                    [
                        'attendance'    => ['status' => $statusValue],
                    ]
                );
            }
        });

        $redirectUrl = route('teacher.attendance.create', ['training_id' => $training->training_id, 'schedule_id' => $scheduleId]);

        if ($request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl,
                'message' => 'Asistencias guardadas correctamente.',
            ]);
        }

        return redirect()
            ->route('teacher.attendance.create', ['training_id' => $training->training_id, 'schedule_id' => $scheduleId])
            ->with('success', 'Asistencias guardadas correctamente.')
            ->with('short_toast', true);
    }

    /**
     * AJAX: check if a schedule has attendances and optionally return them
     */
    public function check(Request $request)
    {
        $request->validate([
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'training_id' => 'nullable|exists:trainings,training_id',
            'date' => 'nullable|date',
        ]);

        $scheduleId = $request->schedule_id;

        if (!$scheduleId && $request->filled('training_id') && $request->filled('date')) {
            $schedule = DB::table('schedules')
                ->where('training_id', $request->training_id)
                ->where('date', $request->date)
                ->first();
            $scheduleId = $schedule->schedule_id ?? null;
        }

        if (!$scheduleId) {
            return response()->json(['exists' => false, 'attendances' => []]);
        }

        $attendances = Attendance::where('schedule_id', $scheduleId)->with('enrollment.student.person')->get();

        $map = $attendances->map(function ($a) {
            $student = optional($a->enrollment)->student;
            $person = optional($student)->person;
            $studentName = optional($person)->first_names . ' ' . optional($person)->last_names;
            $status = $a->attendance;
            if (is_array($status)) {
                $status = $status['status'] ?? null;
            }

            return [
                'enrollment_id' => $a->enrollment_id,
                'student_id' => optional($a->enrollment)->student_id,
                'student_name' => trim($studentName),
                'attendance' => $status,
            ];
        });

        return response()->json(['exists' => $attendances->isNotEmpty(), 'attendances' => $map]);
    }

    /**
     * AJAX: list previous attendances for a training grouped by schedule
     */
    public function listPrevious($training_id)
    {
        $training = Training::with(['schedules'])->findOrFail($training_id);

        $list = collect($training->schedules)->map(function ($s) {
            return [
                'schedule_id' => $s->schedule_id,
                'date' => $s->date ? Carbon::parse($s->date)->format('d/m/Y') : null,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'count' => $s->attendances()->count(),
            ];
        })->sortByDesc(function ($item) {
            return Carbon::createFromFormat('d/m/Y', $item['date'])->timestamp;
        })->values();

        return response()->json(['schedules' => $list]);
    }
}