<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Especialidad;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function main()
    {
        $totalCourses = Course::count();
        $totalStudents = DB::table('usuarios')->count();
        $activeEnrollments = DB::table('matriculas')->where('estado', 'A')->count();
        $activeEvaluations = DB::table('evaluaciones')->where('activo', true)->count();
        $activityProgress = min(100, round($activeEvaluations / max(1, $totalCourses) * 18));

        $upcomingEvaluations = DB::table('evaluaciones')
            ->join('capacitaciones', 'evaluaciones.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select('evaluaciones.titulo', 'cursos.titulo as curso', 'evaluaciones.fechafin')
            ->where('evaluaciones.activo', true)
            ->whereDate('evaluaciones.fechafin', '>=', Carbon::now()->toDateString())
            ->orderBy('evaluaciones.fechafin')
            ->limit(5)
            ->get();

        $popularCourses = DB::table('cursos')
            ->join('capacitaciones', 'cursos.idcurso', '=', 'capacitaciones.idcapacitacion')
            ->join('matriculas', 'capacitaciones.idcapacitacion', '=', 'matriculas.idcapacitacion')
            ->select('cursos.titulo', DB::raw('COUNT(matriculas.idmatricula) as inscritos'))
            ->groupBy('cursos.titulo')
            ->orderByDesc('inscritos')
            ->limit(4)
            ->get();

        return view('dashboard.dashboard', compact(
            'totalCourses',
            'totalStudents',
            'activeEnrollments',
            'activeEvaluations',
            'activityProgress',
            'upcomingEvaluations',
            'popularCourses'
        ));
    }

    public function student()
    {
        $today = Carbon::now();

        $courses = DB::table('matriculas')
            ->join('capacitaciones', 'matriculas.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select(
                'cursos.titulo',
                'cursos.descripcion',
                'capacitaciones.modalidad',
                'matriculas.fechamatricula'
            )
            ->orderByDesc('matriculas.fechamatricula')
            ->limit(6)
            ->get();

        $nextClass = DB::table('horarios')
            ->join('capacitaciones', 'horarios.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select('cursos.titulo', 'horarios.fecha', 'horarios.inicio', 'capacitaciones.modalidad')
            ->whereDate('horarios.fecha', '>=', $today->toDateString())
            ->orderBy('horarios.fecha')
            ->orderBy('horarios.inicio')
            ->first();

        $tasksPending = DB::table('evaluaciones')
            ->where('activo', true)
            ->whereDate('fechafin', '>=', $today->toDateString())
            ->count();

        return view('dashboard.student', compact('courses', 'nextClass', 'tasksPending'));
    }

    public function teacher()
    {
        $courseCount = DB::table('capacitaciones')->count();
        $studentsCount = DB::table('matriculas')->count();
        $pendingTasks = DB::table('evaluaciones')->where('activo', true)->count();
        $averageProgress = 78;

        $upcomingClasses = DB::table('horarios')
            ->join('capacitaciones', 'horarios.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select('cursos.titulo', 'horarios.fecha', 'horarios.inicio', 'capacitaciones.modalidad')
            ->whereDate('horarios.fecha', '>=', Carbon::now()->toDateString())
            ->orderBy('horarios.fecha')
            ->limit(5)
            ->get();

        $topCourses = DB::table('cursos')
            ->join('capacitaciones', 'cursos.idcurso', '=', 'capacitaciones.idcapacitacion')
            ->join('matriculas', 'capacitaciones.idcapacitacion', '=', 'matriculas.idcapacitacion')
            ->select('cursos.titulo', DB::raw('COUNT(matriculas.idmatricula) as inscritos'))
            ->groupBy('cursos.titulo')
            ->orderByDesc('inscritos')
            ->limit(3)
            ->get();

        return view('dashboard.teacher', compact('courseCount', 'studentsCount', 'pendingTasks', 'averageProgress', 'upcomingClasses', 'topCourses'));
    }

    public function admin()
    {
        $courses = Course::count();
        $students = DB::table('usuarios')->count();
        $pendingTasks = DB::table('evaluaciones')->where('activo', true)->count();
        $averageProgress = round(DB::table('evaluaciones')->where('activo', true)->count() * 0.8);

        $recentUsers = DB::table('usuarios')
            ->join('personas', 'usuarios.idpersona', '=', 'personas.idpersona')
            ->select('usuarios.username', 'personas.nombres', 'personas.apellidos', 'usuarios.fechacreacion')
            ->orderByDesc('usuarios.fechacreacion')
            ->limit(4)
            ->get();

        return view('dashboard.admin', compact('courses', 'students', 'pendingTasks', 'averageProgress', 'recentUsers'));
    }

    public function myCourses()
    {
        $courses = DB::table('matriculas')
            ->join('capacitaciones', 'matriculas.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select(
                'cursos.idcurso',
                'cursos.titulo',
                'cursos.descripcion',
                'cursos.cantidadhoras',
                'cursos.precioreferencial',
                'capacitaciones.modalidad',
                'matriculas.fechamatricula'
            )
            ->orderByDesc('matriculas.fechamatricula')
            ->limit(8)
            ->get();

        $enrollmentsCount = DB::table('matriculas')->count();
        $pendingTasks = DB::table('evaluaciones')->where('activo', true)->count();

        return view('dashboard.my-courses', compact('courses', 'enrollmentsCount', 'pendingTasks'));
    }

    public function learningPaths()
    {
        $specialties = DB::table('especialidades')
            ->leftJoin('cursos', 'especialidades.idespecialidad', '=', 'cursos.idespecialidad')
            ->select('especialidades.idespecialidad', 'especialidades.especialidad', DB::raw('COUNT(cursos.idcurso) as cursos_count'))
            ->groupBy('especialidades.idespecialidad', 'especialidades.especialidad')
            ->get();

        $topPaths = Course::select('titulo', 'descripcion')->limit(6)->get();

        return view('dashboard.learning-paths', compact('specialties', 'topPaths'));
    }

    public function progress()
    {
        $totalEnrollments = DB::table('matriculas')->count();
        $activeEvaluations = DB::table('evaluaciones')->where('activo', true)->count();
        $completedCertificates = DB::table('pagos')->count();

        $courseProgress = DB::table('capacitaciones')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->leftJoin('matriculas', 'capacitaciones.idcapacitacion', '=', 'matriculas.idcapacitacion')
            ->select('cursos.titulo', DB::raw('COUNT(matriculas.idmatricula) as inscritos'))
            ->groupBy('cursos.titulo')
            ->orderByDesc('inscritos')
            ->limit(5)
            ->get();

        return view('dashboard.progress', compact('totalEnrollments', 'activeEvaluations', 'completedCertificates', 'courseProgress'));
    }

    public function user()
    {
        $user = DB::table('usuarios')
            ->join('personas', 'usuarios.idpersona', '=', 'personas.idpersona')
            ->select(
                'usuarios.username',
                'usuarios.fechacreacion',
                'personas.nombres',
                'personas.apellidos',
                'personas.email',
                'personas.telefono',
                'personas.direccion'
            )
            ->first();

        $activeEnrollments = DB::table('matriculas')->count();
        $pendingEvaluations = DB::table('evaluaciones')->where('activo', true)->count();

        return view('dashboard.user', compact('user', 'activeEnrollments', 'pendingEvaluations'));
    }

    public function certificates()
    {
        $certificates = DB::table('matriculas')
            ->join('capacitaciones', 'matriculas.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
            ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
            ->select('cursos.titulo', 'matriculas.fechamatricula', 'capacitaciones.modalidad')
            ->where('matriculas.estado', 'A')
            ->orderByDesc('matriculas.fechamatricula')
            ->limit(8)
            ->get();

        return view('dashboard.certificates', compact('certificates'));
    }
}