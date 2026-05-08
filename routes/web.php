<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::post('/enroll/{training}', [EnrollmentController::class, 'store'])
        ->name('enroll.store');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Administrator'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('courses', CourseController::class);
    Route::resource('trainings', TrainingController::class);
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index']);
});

/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:Teacher'])->group(function () {

    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    Route::get('/courses', [TeacherController::class, 'courses'])->name('courses');
    Route::get('/courses/{id}', [TeacherController::class, 'show'])->name('courses.show');

    Route::get('/students/{id}', [TeacherController::class, 'students'])->name('students');

    Route::get('/attendance/{id}', [TeacherController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/store', [TeacherController::class, 'storeAttendance'])->name('attendance.store');

    Route::get('/tasks/create/{training_id}', [TeacherController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks/store', [TeacherController::class, 'storeTask'])->name('tasks.store');
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'role:Student'])->group(function () {

    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');

    Route::get('/courses', [StudentController::class, 'courses'])->name('courses.index');
});
