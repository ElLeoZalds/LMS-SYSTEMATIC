<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Course;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExplorecoursesController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

// LOGIN
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARDS
Route::get('/dashboard/student', [DashboardController::class, 'student'])->name('dashboard.student');

//Profesores
Route::get('/dashboard/teacher', [DashboardController::class, 'teacher'])->name('dashboard.teacher');

//Administradores
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

Route::get('/dashboard/admin/specialty', [SpecialtyController::class, 'index'])
    ->name('modulos.specialtyActions');

Route::get('/dashboard/admin/user', [UsuarioController::class, 'index'])
    ->name('modulos.usuarios');

Route::get('/explore-courses', [ExplorecoursesController::class, 'index'])
    ->name('explore-courses.dashboard');

// Courses CRUD routes
Route::get('/courses', [ExplorecoursesController::class, 'index'])->name('courses.index');
Route::post('/courses', [ExplorecoursesController::class, 'store'])->name('courses.store');
Route::put('/courses/{id}', [ExplorecoursesController::class, 'update'])->name('courses.update');
Route::delete('/courses/{id}', [ExplorecoursesController::class, 'destroy'])->name('courses.destroy');
Route::get('/courses/{id}/enrollments', [ExplorecoursesController::class, 'matriculas'])->name('courses.enrollments');
Route::post('/courses/{id}/enrollments', [ExplorecoursesController::class, 'storeMatricula'])->name('courses.enrollments.store');

// REPORT (separate route)
Route::get('/courses/report', [ExplorecoursesController::class, 'reporte'])
    ->name('courses.report');

// Rutas CRUD Especialidades
Route::get('/especialidades', [SpecialtyController::class, 'index'])->name('especialidades.index');
// Route::get('/especialidades/create', [SpecialtyController::class, 'create'])->name('especialidades.create');
Route::post('/especialidades', [SpecialtyController::class, 'store'])->name('especialidades.store');
Route::get('/especialidades/{id}/edit', [SpecialtyController::class, 'edit'])->name('especialidades.edit');
Route::put('/especialidades/{id}', [SpecialtyController::class, 'update'])->name('especialidades.update');
Route::delete('/especialidades/{id}', [SpecialtyController::class, 'destroy'])->name('especialidades.destroy');

// Rutas CRUD Usuarios
// LISTAR
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

// CREAR
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

// EDITAR
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');

// ACTUALIZAR
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

// ELIMINAR
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

// Rutas de Reportes PDF
Route::get('/reportes/cursos', [ReportController::class, 'coursesPdf'])->name('reportes.cursos');
Route::get('/reportes/especialidades', [ReportController::class, 'specialtiesPdf'])->name('reportes.especialidades');

// Sidebar routes
Route::get('/user', [DashboardController::class, 'user'])->name('dashboard.user');

Route::get('/dashboard/home', [DashboardController::class, 'main'])->name('dashboard.main');

Route::get('/my-courses', [DashboardController::class, 'myCourses'])->name('dashboard.my-courses');

Route::get('/learning-paths', [DashboardController::class, 'learningPaths'])->name('dashboard.learning-paths');

Route::get('/calendar', function (Request $request) {
    $viewMode = $request->query('view', 'week');
    $selectedDate = Carbon::parse($request->query('date', now()));
    $today = Carbon::now();

    $weekStart = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
    $weekEnd = $selectedDate->copy()->endOfWeek(Carbon::SUNDAY);
    $monthStart = $selectedDate->copy()->startOfMonth();
    $monthEnd = $selectedDate->copy()->endOfMonth();

    if ($viewMode === 'month') {
        $calendarStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
    } else {
        $calendarStart = $weekStart;
        $calendarEnd = $weekEnd;
    }

    $rawEvents = DB::table('horarios')
        ->join('capacitaciones', 'horarios.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
        ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
        ->whereBetween('fecha', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
        ->select('horarios.*', 'cursos.titulo as curso', 'capacitaciones.modalidad')
        ->orderBy('fecha')
        ->orderBy('inicio')
        ->get();

    $eventsByDate = [];
    foreach ($rawEvents as $event) {
        $eventsByDate[$event->fecha][] = $event;
    }

    $calendarDays = [];
    for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
        $calendarDays[] = [
            'date' => $date->copy(),
            'events' => $eventsByDate[$date->toDateString()] ?? [],
            'currentMonth' => $date->month === $selectedDate->month,
        ];
    }

    $calendarWeeks = array_chunk($calendarDays, 7);

    $classesThisWeek = DB::table('horarios')
        ->whereBetween('fecha', [$today->copy()->startOfWeek(Carbon::MONDAY)->toDateString(), $today->copy()->endOfWeek(Carbon::SUNDAY)->toDateString()])
        ->count();

    $tasksPending = DB::table('evaluaciones')
        ->where('activo', true)
        ->whereBetween('fechafin', [$today->toDateString(), $today->copy()->addDays(7)->toDateString()])
        ->count();

    $upcomingTasks = DB::table('evaluaciones')
        ->join('capacitaciones', 'evaluaciones.idcapacitacion', '=', 'capacitaciones.idcapacitacion')
        ->join('cursos', 'capacitaciones.idcurso', '=', 'cursos.idcurso')
        ->where('evaluaciones.activo', true)
        ->whereBetween('evaluaciones.fechafin', [$today->toDateString(), $today->copy()->addDays(14)->toDateString()])
        ->select('evaluaciones.titulo', 'cursos.titulo as curso', 'evaluaciones.fechafin')
        ->orderBy('evaluaciones.fechafin')
        ->limit(5)
        ->get();

    $prevDate = $viewMode === 'month'
        ? $selectedDate->copy()->subMonth()->format('Y-m-d')
        : $selectedDate->copy()->subWeek()->format('Y-m-d');

    $nextDate = $viewMode === 'month'
        ? $selectedDate->copy()->addMonth()->format('Y-m-d')
        : $selectedDate->copy()->addWeek()->format('Y-m-d');

    return view('dashboard.calendar', compact(
        'viewMode',
        'selectedDate',
        'weekStart',
        'weekEnd',
        'monthStart',
        'monthEnd',
        'calendarWeeks',
        'classesThisWeek',
        'tasksPending',
        'upcomingTasks',
        'prevDate',
        'nextDate'
    ));
})->name('dashboard.calendar');

Route::get('/certificates', [DashboardController::class, 'certificates'])
    ->name('dashboard.certificates');

Route::get('/progress', [DashboardController::class, 'progress'])
    ->name('dashboard.progress');


// ACCIONES
Route::get('/actions', function () {
    return view('actions.index');
});

// MODULOS
Route::prefix('modulos')->group(function () {

    Route::get('/people', function () {
        return view('modulos.people');
    });

    Route::get('/user', function () {
        return view('modulos.user');
    });

    Route::get('/role', function () {
        return view('modulos.role');
    });

    Route::get('/specialty', function () {
        return view('modulos.specialty');
    });

    Route::get('/user-role', function () {
        return view('modulos.user_role');
    });

    Route::get('/course', function () {
        return view('modulos.course');
    });

    Route::get('/training', function () {
        return view('modulos.training');
    });

    Route::get('/pay-method', function () {
        return view('modulos.pay_method');
    });

    Route::get('/registration', function () {
        return view('modulos.registration');
    });
});



