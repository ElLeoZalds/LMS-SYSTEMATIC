<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    private function isAdministrator(): bool
    {
        return auth()->user()?->roles->contains('name', 'Administrator') ?? false;
    }

    public function create(Request $request)
    {
        $request->validate([
            'training_id' => 'nullable|exists:trainings,training_id',
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'date' => 'nullable|date',
        ]);

        $user = auth()->user();
        $trainings = Training::with('course')
            ->when(! $this->isAdministrator(), fn ($query) => $query->where('teacher_id', $user->user_id))
            ->get();

        $training = null;
        $date = $request->date ?? date('Y-m-d');
        $selectedScheduleId = null;
        $attendances = collect();

        if ($request->filled('training_id')) {
            $training = $trainings->firstWhere('training_id', $request->training_id);

            if (! $training) {
                abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
            }

            $training->load(['enrollments.student.person', 'schedules']);

            if ($request->filled('schedule_id')) {
                $schedule = $this->scheduleForTraining($request->schedule_id, $training->training_id);

                if ($schedule) {
                    $selectedScheduleId = $schedule->schedule_id;
                    $attendances = Attendance::where('schedule_id', $selectedScheduleId)->with('enrollment')->get();
                    $date = $schedule->date ?? $date;
                } else {
                    $attendances = collect();
                }
            }
        }

        return view('teacher.attendance', compact('trainings', 'training', 'date', 'attendances', 'selectedScheduleId'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $training = Training::findOrFail($data['training_id']);

        if (! $this->isAdministrator() && $training->teacher_id !== auth()->user()->user_id) {
            abort(403, 'No tienes permiso para registrar asistencias en esta capacitación.');
        }

        $scheduleId = $data['schedule_id'];
        $schedule = $this->scheduleForTraining($scheduleId, $training->training_id);

        if (! $schedule) {
            return back()->with('error', 'Sesión no válida para esta capacitación.')->withInput();
        }

        if ($schedule->date && $schedule->date > date('Y-m-d')) {
            return back()->with('error', 'No se puede tomar asistencia para una fecha futura.')->withInput();
        }

        DB::transaction(fn () => $this->saveAttendances($data['attendances'], $scheduleId, $training->training_id));

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

    public function check(Request $request)
    {
        $request->validate([
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'training_id' => 'nullable|exists:trainings,training_id',
            'date' => 'nullable|date',
        ]);

        $scheduleId = $request->schedule_id;

        if (! $scheduleId && $request->filled('training_id') && $request->filled('date')) {
            $schedule = DB::table('schedules')
                ->where('training_id', $request->training_id)
                ->where('date', $request->date)
                ->first();
            $scheduleId = $schedule->schedule_id ?? null;
        }

        if (! $scheduleId) {
            return response()->json(['exists' => false, 'attendances' => []]);
        }

        $attendances = Attendance::where('schedule_id', $scheduleId)->with('enrollment.student.person')->get();

        $map = $attendances->map(function ($a) {
            $student = optional($a->enrollment)->student;
            $person = optional($student)->person;
            $studentName = optional($person)->first_names.' '.optional($person)->last_names;
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

    private function validatedData(Request $request): array
    {
        return $request->validate([
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
    }

    private function scheduleForTraining(int $scheduleId, int $trainingId)
    {
        return DB::table('schedules')
            ->where('schedule_id', $scheduleId)
            ->where('training_id', $trainingId)
            ->first();
    }

    private function saveAttendances(array $attendances, int $scheduleId, int $trainingId): void
    {
        foreach ($attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'enrollment_id' => $this->enrollmentId($trainingId, $attendanceData['student_id']),
                ],
                [
                    'attendance' => ['status' => $this->attendanceStatus($attendanceData['status'])],
                ]
            );
        }
    }

    private function enrollmentId(int $trainingId, int $studentId): int
    {
        return DB::table('enrollments')
            ->where('training_id', $trainingId)
            ->where('student_id', $studentId)
            ->value('enrollment_id') ?? $studentId;
    }

    private function attendanceStatus(string $status): string
    {
        return match ($status) {
            'P' => 'present',
            'A' => 'absent',
            'J' => 'justified',
            'T' => 'late',
            default => 'absent',
        };
    }
}
