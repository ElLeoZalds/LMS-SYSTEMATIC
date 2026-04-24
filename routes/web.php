<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Controllers\ExplorecoursesController;

// LOGIN
Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {


    $role = $request->input('role');

    if ($role === 'teacher') {
        return redirect('/dashboard/teacher');
    }

    return redirect('/dashboard/student');
});

// DASHBOARDS
Route::get('/dashboard/student', function () {
    return view('dashboard.student');
});

//Profesores
Route::get('/dashboard/teacher', function () {
    return view('dashboard.teacher');
})->name('dashboard.teacher');

//Administradores
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');

// Route::get('/dashboard/admin/specialty', function () {
//     return view('modulos.specialty');
// })->name('modulos.specialty');

Route::get('/explorar-cursos', [ExplorecoursesController::class, 'index'])
    ->name('explore-courses.dashboard');

// Rutas CRUD Cursos
Route::get('/cursos', [ExplorecoursesController::class, 'index'])->name('cursos.index');
Route::get('/cursos/create', [ExplorecoursesController::class, 'create'])->name('cursos.create');
Route::post('/cursos', [ExplorecoursesController::class, 'store'])->name('cursos.store');
Route::get('/cursos/{id}/edit', [ExplorecoursesController::class, 'edit'])->name('cursos.edit');
Route::put('/cursos/{id}', [ExplorecoursesController::class, 'update'])->name('cursos.update');
Route::delete('/cursos/{id}', [ExplorecoursesController::class, 'destroy'])->name('cursos.destroy');

// Rutas Sidebar
Route::get('/usuario', function () {
    return view('dashboard.usuario');
})->name('dashboard.usuario');

Route::get('/mi-dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard.main');

Route::get('/mis-cursos', function () {
    return view('dashboard.mis-cursos');
})->name('dashboard.mis-cursos');

Route::get('/learning-paths', function () {
    return view('dashboard.learning-paths');
})->name('dashboard.learning-paths');

Route::get('/calendario', function () {
    return view('dashboard.calendario');
})->name('dashboard.calendario');

Route::get('/certificados', function () {
    return view('dashboard.certificados');
})->name('dashboard.certificados');

Route::get('/progreso', function () {
    return view('dashboard.progreso');
})->name('dashboard.progreso');


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
