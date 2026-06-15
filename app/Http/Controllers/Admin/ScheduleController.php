<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['training.course', 'training.teacher.person'])
            ->get()
            ->sortBy([
                fn ($schedule) => optional($schedule->training->course)->title ?? '',
                'date',
            ]);

        $trainings = Training::where('status', 1)->get();

        return view('admin.schedules.index', compact('schedules', 'trainings'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $trainings = Training::where('status', 1)->with('course')->get();

        return view('admin.schedules.create', compact('trainings'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Obtener el profesor asignado a la capacitación actual
        $training = Training::findOrFail($request->training_id);
        $teacherId = $training->teacher_id;

        // Verificar si el profesor ya tiene un compromiso que se cruce en esa fecha y rango horario
        $overlap = Schedule::where('date', $request->date)
            ->whereHas('training', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_time' => 'El profesor asignado ya tiene otra capacitación programada que se cruza en este rango de horario.']);
        }

        Schedule::create([
            'training_id' => $request->training_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Horario creado correctamente.');
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $trainings = Training::where('status', 1)->with('course')->get();

        return view('admin.schedules.edit', compact('schedule', 'trainings'));
    }

    /**
     * Store multiple schedules for a training from the trainings page.
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_id' => 'required|exists:trainings,training_id',
            'schedules' => 'required|array|min:1',
            'schedules.*.date' => 'required|date|after_or_equal:today',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Revisa los datos de los horarios.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $training = Training::findOrFail($request->training_id);

        foreach ($request->input('schedules', []) as $index => $scheduleData) {
            // Validar que la fecha esté dentro del rango del entrenamiento
            if ($training->start_date && $training->end_date) {
                $scheduleDate = Carbon::createFromFormat('Y-m-d', $scheduleData['date']);

                if ($scheduleDate->lt($training->start_date) || $scheduleDate->gt($training->end_date)) {
                    $validator->errors()->add(
                        "schedules.$index.date",
                        "La fecha debe estar entre {$training->start_date->format('d/m/Y')} y {$training->end_date->format('d/m/Y')}."
                    );
                }
            }

            if (($scheduleData['end_time'] ?? null) <= ($scheduleData['start_time'] ?? null)) {
                $validator->errors()->add("schedules.$index.end_time", 'La hora fin debe ser posterior a la hora inicio.');
            }

            $overlap = Schedule::where('date', $scheduleData['date'])
                ->whereHas('training', function ($query) use ($training) {
                    $query->where('teacher_id', $training->teacher_id);
                })
                ->where(function ($query) use ($scheduleData) {
                    $query->where('start_time', '<', $scheduleData['end_time'])
                        ->where('end_time', '>', $scheduleData['start_time']);
                })
                ->exists();

            if ($overlap) {
                $validator->errors()->add("schedules.$index.start_time", 'El profesor ya tiene otra capacitación programada en ese rango horario.');
            }
        }

        if ($validator->errors()->any()) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No fue posible guardar todos los horarios.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($training, $request) {
            foreach ($request->input('schedules', []) as $scheduleData) {
                Schedule::create([
                    'training_id' => $training->training_id,
                    'date' => $scheduleData['date'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                ]);
            }
        });

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Horarios guardados correctamente.',
            ]);
        }

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Horarios guardados correctamente.');
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'training_id' => 'required|exists:trainings,training_id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.schedules.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Obtener el profesor asignado a la capacitación actual
        $training = Training::findOrFail($request->training_id);
        $teacherId = $training->teacher_id;

        // Verificar si el profesor ya tiene otro compromiso que se cruce en esa fecha y rango horario
        $overlap = Schedule::where('schedule_id', '!=', $id)
            ->where('date', $request->date)
            ->whereHas('training', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_time' => 'El profesor asignado ya tiene otra capacitación programada que se cruza en este rango de horario.']);
        }

        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'training_id' => $request->training_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Horario actualizado correctamente.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Horario eliminado correctamente.');
    }
}
