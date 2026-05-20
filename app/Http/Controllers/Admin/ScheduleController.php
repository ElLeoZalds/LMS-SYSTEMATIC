<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Training;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['training.course', 'training.teacher.person'])
            ->orderBy('date', 'asc')
            ->get();

        $trainings = Training::where('status', 'A')->get();

        return view('admin.schedules.index', compact('schedules', 'trainings'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $trainings = Training::where('status', 'A')->with('course')->get();

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
        $trainings = Training::where('status', 'A')->with('course')->get();

        return view('admin.schedules.edit', compact('schedule', 'trainings'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,training_id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

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
