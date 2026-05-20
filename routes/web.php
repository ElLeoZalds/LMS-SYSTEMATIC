<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\TaskController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\PaymentController;
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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
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
    Route::post('trainings/{training}/enroll', [TrainingController::class, 'enroll'])->name('trainings.enroll');
    Route::get('enrollments/create', [AdminEnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('enrollments/store', [AdminEnrollmentController::class, 'store'])->name('enrollments.store');
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('contents', ContentController::class);
    Route::resource('payments', PaymentController::class);
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

    Route::get('/attendance/create', [TeacherAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [TeacherAttendanceController::class, 'store'])->name('attendance.store');

    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task_id}/submissions', [TaskController::class, 'submissions'])->name('tasks.submissions');
    Route::post('/submissions/{submission_id}/grade', [TaskController::class, 'grade'])->name('submissions.grade');

    // Rutas de Assessments estandarizadas
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    
    // Todas usan {training_id} para acceder al curso y {assessment_id} para la evaluación específica
    Route::get('/assessments/{training_id}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::put('/assessments/{assessment_id}', [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment_id}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    
    Route::post('assessments/{assessment_id}/questions', [AssessmentController::class, 'addQuestion'])->name('assessments.questions.store');
    Route::put('questions/{question_id}', [AssessmentController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('questions/{question_id}', [AssessmentController::class, 'destroyQuestion'])->name('questions.destroy');
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'role:Student'])->group(function () {

    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');

    Route::get('/courses', [StudentController::class, 'courses'])->name('courses.index');
    Route::get('/courses/{id}', [StudentCourseController::class, 'show'])->name('courses.show');

    Route::get('/assessment/{id}/take', [StudentCourseController::class, 'takeExam'])->name('assessment.take');
    Route::post('/assessment/{id}/submit', [StudentCourseController::class, 'submitExam'])->name('assessment.submit');
});