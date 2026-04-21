<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// LOGIN
Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {

    // Simulación simple de roles
    $role = $request->input('role'); // luego lo cambias por BD

    if ($role === 'teacher') {
        return redirect('/dashboard/teacher');
    }

    return redirect('/dashboard/student');
});

// DASHBOARDS
Route::get('/dashboard/student', function () {
    return view('dashboard.student');
});

Route::get('/dashboard/teacher', function () {
    return view('dashboard.teacher');
})->name('dashboard.teacher');

// CURSOS
Route::get('/curso/{slug}', function ($slug) {
    return view('courses.manage', compact('slug'));
});

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