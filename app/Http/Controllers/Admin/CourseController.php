<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialty;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('specialty')
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

        Course::create([
            'specialty_id' => $data['specialty_id'],
            'title' => $data['title'],
            'abbreviation' => $this->generateAbbreviation($data['title']),
            'description' => $data['description'],
            'hours_count' => $data['hours_count'],
            'reference_price' => $data['reference_price'],
            'banner_path' => $data['banner_path'] ?? null,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado correctamente');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $specialties = Specialty::orderBy('specialty', 'asc')->get();

        return view('admin.courses.edit', compact('course', 'specialties'));
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
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado correctamente');
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
