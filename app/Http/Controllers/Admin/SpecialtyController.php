<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::withCount([
            'courses' => fn ($q) => $q->where('is_active', true),
        ])->orderBy('created_at', 'desc')->get();

        $specialties->each(function ($specialty) {
            $specialty->active_trainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
                ->where('status', Training::STATUS_ACTIVE)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>', now());
                })
                ->count();

            $specialty->finished_trainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
                ->where(function ($q) {
                    $q->where('status', Training::STATUS_FINISHED)
                        ->orWhere(fn ($subQ) => $subQ->where('status', Training::STATUS_ACTIVE)
                            ->where('end_date', '<=', now()));
                })
                ->count();

            $specialty->archived_trainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
                ->where('status', Training::STATUS_ARCHIVED)
                ->count();
        });

        return view('admin.specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('admin.specialties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialty' => 'required|string|max:100|unique:specialties,specialty',
        ]);

        Specialty::create([
            'specialty' => $request->specialty,
            'is_active' => true,
        ]);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad creada correctamente');
    }

    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);

        return view('admin.specialties.edit', compact('specialty'));
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $request->validate([
            'specialty' => 'required|string|max:100|unique:specialties,specialty,'.$id.',specialty_id',
        ]);

        $specialty->update([
            'specialty' => $request->specialty,
        ]);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad actualizada correctamente');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.specialties.index')
            ->with('error', 'La eliminación no está permitida. Use la opción de desactivar para ocultar esta especialidad.');
    }

    public function toggleActive($id)
    {
        $specialty = Specialty::findOrFail($id);
        $isActive = $specialty->isActive();

        if (! $isActive) {
            // Activar la especialidad
            $specialty->update(['is_active' => true]);
            $specialty->courses()->update(['is_active' => true]);

            return redirect()->route('admin.specialties.index')
                ->with('success', 'Especialidad activada correctamente. Los cursos asociados también se han activado.');
        }

        // Verificar si se puede desactivar
        $activeTrainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
            ->where('status', Training::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->count();

        if ($activeTrainings > 0) {
            return redirect()->route('admin.specialties.index')
                ->with('error', "No se puede desactivar la especialidad '{$specialty->specialty}' porque tiene {$activeTrainings} capacitaciones en curso. Finalice o archive esas capacitaciones primero.");
        }

        // Desactivar la especialidad y sus cursos
        DB::transaction(function () use ($specialty) {
            $specialty->update(['is_active' => false]);
            $specialty->courses()->update(['is_active' => false]);

            // Archivar capacitaciones finalizadas
            Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
                ->where('status', Training::STATUS_ACTIVE)
                ->where('end_date', '<=', now())
                ->update(['status' => Training::STATUS_ARCHIVED]);
        });

        $finishedTrainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
            ->where(fn ($q) => $q->where('status', Training::STATUS_ARCHIVED)
                ->orWhere('status', Training::STATUS_FINISHED))
            ->count();

        return redirect()->route('admin.specialties.index')
            ->with('success', "Especialidad desactivada. Sus {$finishedTrainings} capacitaciones finalizadas permanecen accesibles para consulta de estudiantes.");
    }

    public function canDeactivate($id)
    {
        $specialty = Specialty::findOrFail($id);

        $activeTrainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
            ->where('status', Training::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->count();

        $finishedTrainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
            ->where(function ($q) {
                $q->where('status', Training::STATUS_FINISHED)
                    ->orWhere(fn ($subQ) => $subQ->where('status', Training::STATUS_ACTIVE)->where('end_date', '<=', now()));
            })
            ->count();

        $archivedTrainings = Training::whereHas('course', fn ($q) => $q->where('specialty_id', $specialty->specialty_id))
            ->where('status', Training::STATUS_ARCHIVED)
            ->count();

        return response()->json([
            'can_deactivate' => $activeTrainings === 0,
            'reason' => $activeTrainings > 0 ? "Tiene {$activeTrainings} capacitaciones en curso" : null,
            'active_trainings' => $activeTrainings,
            'finished_trainings' => $finishedTrainings,
            'archived_trainings' => $archivedTrainings,
        ]);
    }
}
