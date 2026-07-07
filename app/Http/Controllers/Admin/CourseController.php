<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Specialty;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('specialty')
            ->withCount('modules')
            ->orderBy('created_at', 'desc')
            ->get();

        $specialties = Specialty::orderBy('specialty', 'asc')->get();

        return view('admin.courses.index', compact('courses', 'specialties'));
    }

    public function create()
    {
        $specialties = Specialty::orderBy('specialty', 'asc')->get();

        return view('admin.courses.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->courseValidationRules());

        $data['title'] = trim($data['title']);
        $data['description'] = $data['description'] !== null ? trim($data['description']) : null;

        $course = Course::create([
            'specialty_id' => $data['specialty_id'],
            'title' => $data['title'],
            'abbreviation' => $this->generateAbbreviation($data['title']),
            'description' => $data['description'],
            'hours_count' => $data['hours_count'],
            'reference_price' => $data['reference_price'],
            'banner_path' => $data['banner_path'] ?? null,
            'is_active' => true,
        ]);

        $modulesData = $request->input('modules_data');

        if (! empty($modulesData)) {
            $parsedModules = json_decode((string) $modulesData, true);

            if (is_array($parsedModules)) {
                foreach ($parsedModules as $moduleData) {
                    if (! is_array($moduleData)) {
                        continue;
                    }

                    $moduleTitle = trim((string) ($moduleData['title'] ?? ''));

                    if ($moduleTitle === '') {
                        continue;
                    }

                    $course->modules()->create([
                        'title' => $moduleTitle,
                        'description' => isset($moduleData['description']) ? trim((string) $moduleData['description']) : null,
                        'order' => (int) ($moduleData['order'] ?? 0),
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Curso creado correctamente');
    }

    public function edit($id)
    {
        $course = Course::with('modules')->findOrFail($id);
        $modules = $course->modules()->orderBy('order')->orderBy('title')->get();
        $specialties = Specialty::orderBy('specialty', 'asc')->get();

        return view('admin.courses.edit', compact('course', 'modules', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $data = $request->validate($this->courseValidationRules());

        $data['title'] = trim($data['title']);
        $data['description'] = $data['description'] !== null ? trim($data['description']) : null;
        $data['abbreviation'] = $this->generateAbbreviation($data['title'], $course);

        $course->update($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado correctamente');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.courses.index')
            ->with('error', 'La eliminación no está permitida. Use la opción de desactivar para ocultar este curso.');
    }

    public function toggleActive($id)
    {
        $course = Course::findOrFail($id);
        $isActive = $course->isActive();

        if (! $isActive) {
            // Activar el curso
            $course->update(['is_active' => true]);

            return redirect()->route('admin.courses.index')
                ->with('success', 'Curso activado correctamente.');
        }

        // Verificar si se puede desactivar
        $activeTrainings = Training::where('course_id', $course->course_id)
            ->where('status', Training::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->count();

        if ($activeTrainings > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', "No se puede desactivar el curso '{$course->title}' porque tiene {$activeTrainings} capacitaciones en curso. Finalice o archive esas capacitaciones primero.");
        }

        // Desactivar el curso
        DB::transaction(function () use ($course) {
            $course->update(['is_active' => false]);

            // Archivar capacitaciones finalizadas
            Training::where('course_id', $course->course_id)
                ->where('status', Training::STATUS_ACTIVE)
                ->where('end_date', '<=', now())
                ->update(['status' => Training::STATUS_ARCHIVED]);
        });

        $finishedTrainings = Training::where('course_id', $course->course_id)
            ->where(fn ($q) => $q->where('status', Training::STATUS_ARCHIVED)
                ->orWhere('status', Training::STATUS_FINISHED))
            ->count();

        return redirect()->route('admin.courses.index')
            ->with('success', "Curso desactivado. Sus {$finishedTrainings} capacitaciones finalizadas permanecen accesibles para consulta de estudiantes.");
    }

    public function canDeactivate($id)
    {
        $course = Course::findOrFail($id);

        $activeTrainings = Training::where('course_id', $course->course_id)
            ->where('status', Training::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->count();

        $finishedTrainings = Training::where('course_id', $course->course_id)
            ->where(function ($q) {
                $q->where('status', Training::STATUS_FINISHED)
                    ->orWhere(fn ($subQ) => $subQ->where('status', Training::STATUS_ACTIVE)->where('end_date', '<=', now()));
            })
            ->count();

        $archivedTrainings = Training::where('course_id', $course->course_id)
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

    public function modules($course_id)
    {
        $course = Course::findOrFail($course_id);

        return response()->json($course->modules()->orderBy('order')->orderBy('title')->get());
    }

    public function storeModule(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150', Rule::unique('modules', 'title')->where(fn ($query) => $query->where('course_id', $course->course_id))],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $course->modules()->create([
            'title' => trim($data['title']),
            'description' => $data['description'] !== null ? trim($data['description']) : null,
            'order' => $data['order'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Módulo creado correctamente.');
    }

    public function updateModule(Request $request, $course_id, $module_id)
    {
        $course = Course::findOrFail($course_id);
        $module = $course->modules()->findOrFail($module_id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150', Rule::unique('modules', 'title')->where(fn ($query) => $query->where('course_id', $course->course_id))->ignore($module->id)],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $module->update([
            'title' => trim($data['title']),
            'description' => $data['description'] !== null ? trim($data['description']) : null,
            'order' => $data['order'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Módulo actualizado correctamente.');
    }

    public function toggleModuleActive($course_id, $module_id)
    {
        $course = Course::findOrFail($course_id);
        $module = $course->modules()->findOrFail($module_id);

        $hasSubmissions = $module->tasks()->whereHas('submissions')->exists();
        $hasAttempts = $module->assessments()->whereHas('attempts')->exists();

        if ($module->is_active && ($hasSubmissions || $hasAttempts)) {
            return redirect()->route('admin.courses.edit', $course)
                ->with('error', 'No se puede desactivar este módulo porque ya contiene entregas o intentos registrados.');
        }

        $module->update(['is_active' => ! $module->is_active]);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', $module->fresh()->is_active ? 'Módulo activado correctamente.' : 'Módulo desactivado correctamente.');
    }

    private function courseValidationRules(): array
    {
        return [
            'specialty_id' => ['required', 'exists:specialties,specialty_id'],
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'hours_count' => ['nullable', 'integer', 'min:1'],
            'reference_price' => ['nullable', 'numeric', 'min:0'],
            'banner_path' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function generateAbbreviation(?string $title, ?Course $course = null): string
    {
        $titleToUse = trim((string) ($title ?? ''));
        $currentTitle = trim((string) ($course?->title ?? ''));

        if ($course && ! empty($course->abbreviation) && $titleToUse === $currentTitle) {
            return $course->abbreviation;
        }

        $base = $this->buildBaseAbbreviation($titleToUse);
        $candidate = mb_strtoupper($base);
        $counter = 1;

        while (Course::where('abbreviation', $candidate)
            ->when($course, fn ($query) => $query->where('course_id', '!=', $course->course_id))
            ->exists()) {
            $candidate = mb_strtoupper($base.$counter);
            $counter++;
        }

        return $candidate;
    }

    private function buildBaseAbbreviation(string $title): string
    {
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $title);
        $words = preg_split('/\s+/', trim((string) $normalized)) ?: [];
        $filteredWords = [];
        $stopWords = ['de', 'del', 'la', 'las', 'el', 'los', 'y', 'e', 'a', 'an', 'para', 'por', 'con', 'of', 'the', 'and', 'for', 'in', 'on'];

        foreach ($words as $word) {
            $cleanWord = mb_strtolower($word);

            if ($cleanWord === '') {
                continue;
            }

            if (in_array($cleanWord, $stopWords, true)) {
                continue;
            }

            $filteredWords[] = $word;
        }

        if (empty($filteredWords)) {
            return 'COURSE';
        }

        if (count($filteredWords) === 1) {
            $word = mb_substr($filteredWords[0], 0, 4);

            return mb_strtoupper($word);
        }

        $firstWord = $filteredWords[0];
        $secondWord = $filteredWords[1];
        $firstPart = mb_substr($firstWord, 0, 2);
        $secondPart = mb_substr($secondWord, 0, 3);

        return mb_strtoupper($firstPart.$secondPart);
    }
}
