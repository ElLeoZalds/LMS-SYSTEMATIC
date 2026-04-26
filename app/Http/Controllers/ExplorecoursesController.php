<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Especialidad;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExplorecoursesController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $especialidades = Especialidad::all();
        return view('dashboard.courses.index', compact('courses', 'especialidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idespecialidad' => 'required|exists:especialidades,idespecialidad',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidadhoras' => 'required|integer',
            'precioreferencial' => 'required|numeric',
            'pathbanner' => 'nullable|string',
        ]);
        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'idespecialidad' => 'required|exists:especialidades,idespecialidad',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidadhoras' => 'required|integer',
            'precioreferencial' => 'required|numeric',
            'pathbanner' => 'nullable|string',
        ]);
        $course = Course::findOrFail($id);
        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Curso eliminado correctamente');
    }

    public function matriculas($id)
    {
        $course = Course::findOrFail($id);

        $capacitaciones = DB::table('capacitaciones')
            ->where('idcurso', $course->idcurso)
            ->get();

        $students = DB::table('usuarios as u')
            ->join('usuariosroles as ur', 'u.idusuario', '=', 'ur.idusuario')
            ->join('roles as r', 'ur.idrol', '=', 'r.idrol')
            ->join('personas as p', 'u.idpersona', '=', 'p.idpersona')
            ->where('r.nombre', 'Estudiante')
            ->select('u.idusuario', 'u.username', 'p.nombres', 'p.apellidos')
            ->get();

        $enrollments = DB::table('matriculas as m')
            ->join('capacitaciones as c', 'm.idcapacitacion', '=', 'c.idcapacitacion')
            ->join('usuarios as u', 'm.idalumno', '=', 'u.idusuario')
            ->join('personas as p', 'u.idpersona', '=', 'p.idpersona')
            ->where('c.idcurso', $course->idcurso)
            ->select('m.idmatricula', 'm.fechamatricula', 'p.nombres', 'p.apellidos', 'm.estado', 'c.modalidad')
            ->get();

        return view('dashboard.courses.enroll', compact('course', 'capacitaciones', 'students', 'enrollments'));
    }

    public function storeMatricula(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'idcapacitacion' => 'required|exists:capacitaciones,idcapacitacion',
            'idalumno' => 'required|exists:usuarios,idusuario',
        ]);

        $capacitacion = DB::table('capacitaciones')
            ->where('idcapacitacion', $validated['idcapacitacion'])
            ->where('idcurso', $course->idcurso)
            ->first();

        if (! $capacitacion) {
            return redirect()->back()->with('error', 'La capacitación seleccionada no pertenece a este curso.');
        }

        $alreadyEnrolled = DB::table('matriculas')
            ->where('idcapacitacion', $validated['idcapacitacion'])
            ->where('idalumno', $validated['idalumno'])
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->with('error', 'El estudiante ya está inscrito en esta capacitación.');
        }

        DB::table('matriculas')->insert([
            'idcapacitacion' => $validated['idcapacitacion'],
            'idalumno' => $validated['idalumno'],
            'idadministrador' => 1,
            'fechamatricula' => now()->toDateString(),
            'estado' => 'A',
        ]);

        return redirect()->route('courses.enrollments', $course->idcurso)->with('success', 'Estudiante agregado al curso correctamente.');
    }

    public function reporte()
    {
        $courses = Course::all();

        // Renderizar vista Blade a HTML
        $html = view('dashboard.courses.report', compact('courses'))->render();

        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($html);

        return response(
            $html2pdf->output('reporte_cursos.pdf'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="reporte_cursos.pdf"'
            ]
        );
    }
}
