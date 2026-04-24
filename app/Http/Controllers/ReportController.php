<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function coursesPdf()
    {
        $courses = Course::with('especialidad')->get();
        $pdf = PDF::loadView('reports.courses', compact('courses'));
        return $pdf->download('reporte_cursos.pdf');
    }

    public function specialtiesPdf()
    {
        $especialidades = Especialidad::withCount('cursos')->get();
        $pdf = PDF::loadView('reports.specialties', compact('especialidades'));
        return $pdf->download('reporte_especialidades.pdf');
    }
}