<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\TaskController;
use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('throttle:10,1')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/select-role', [AuthController::class, 'showRoleSelector'])->name('role.select');
    Route::post('/select-role', [AuthController::class, 'setActiveRole'])->name('role.set');
    Route::get('/switch-role/{roleId}', [AuthController::class, 'switchRole'])->name('role.set.quick');

    Route::post('/enroll/{training}', [EnrollmentController::class, 'store'])
        ->name('enroll.store');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:Administrator'])
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('courses', CourseController::class);
        Route::patch('courses/{course}/toggle-active', [CourseController::class, 'toggleActive'])->name('courses.toggle-active');
        Route::get('courses/{course}/can-deactivate', [CourseController::class, 'canDeactivate'])->name('courses.can-deactivate');
        Route::get('courses/{course}/modules', [CourseController::class, 'modules'])->name('courses.modules');
        Route::post('courses/{course}/modules', [CourseController::class, 'storeModule'])->name('courses.modules.store');
        Route::patch('courses/{course}/modules/{module}', [CourseController::class, 'updateModule'])->name('courses.modules.update');
        Route::patch('courses/{course}/modules/{module}/toggle-active', [CourseController::class, 'toggleModuleActive'])->name('courses.modules.toggle-active');
        // Route::resource('modules', ModuleController::class)->names('modules');
        // Route::patch('modules/{module}/toggle-active', [ModuleController::class, 'toggleActive'])->name('modules.toggle-active');
        Route::resource('specialties', SpecialtyController::class);
        Route::patch('specialties/{specialty}/toggle-active', [SpecialtyController::class, 'toggleActive'])->name('specialties.toggle-active');
        Route::get('specialties/{specialty}/can-deactivate', [SpecialtyController::class, 'canDeactivate'])->name('specialties.can-deactivate');
        Route::resource('users', UserController::class)->except(['edit', 'update']);
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('students/{user}', [AdminStudentController::class, 'show'])->name('students.show');
        Route::resource('contents', ContentController::class);
        Route::resource('payments', PaymentController::class);

        Route::patch('trainings/{training}/toggle-active', [TrainingController::class, 'toggleActive'])->name('trainings.toggle-active');
        Route::resource('trainings', TrainingController::class);
        Route::post('trainings/{training}/enroll', [TrainingController::class, 'enroll'])->name('trainings.enroll');

        Route::get('enrollments/create', [AdminEnrollmentController::class, 'create'])->name('enrollments.create');
        Route::post('enrollments/store', [AdminEnrollmentController::class, 'store'])->name('enrollments.store');

        Route::post('schedules/bulk-store', [ScheduleController::class, 'bulkStore'])->name('schedules.bulk-store');
        Route::resource('schedules', ScheduleController::class);
    });

Route::prefix('teacher')
    ->name('teacher.')
    ->middleware(['auth', 'role:Teacher'])
    ->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/calendar', [TeacherController::class, 'calendar'])->name('calendar');

        Route::get('/courses', [TeacherController::class, 'courses'])->name('courses');
        Route::get('/courses/{id}', [TeacherController::class, 'show'])->name('courses.show');
        Route::get('/courses/{training}/export-gradebook/{module_id?}', [TeacherController::class, 'exportGradebook'])->name('courses.export-gradebook');
        Route::get('/courses/{id}/report', [TeacherController::class, 'report'])->name('courses.report');
        Route::get('/courses/{id}/report/asistencias', [TeacherController::class, 'reportAttendance'])->name('courses.report.attendance');
        Route::post('/courses/{id}/banner', [TeacherController::class, 'uploadBanner'])->name('courses.banner.upload');
        Route::post('/courses/{id}/announcements', [TeacherController::class, 'storeAnnouncement'])->name('courses.announcements.store');

        Route::get('/students/{id}', [TeacherController::class, 'students'])->name('students');
        Route::get('/ajax/students/{id}', [TeacherController::class, 'ajaxStudents'])->name('ajax.students');
        Route::get('/ajax/averages/{id}', [TeacherController::class, 'ajaxAverages'])->name('ajax.averages');

        Route::get('/attendance/create', [TeacherAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance/store', [TeacherAttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/check', [TeacherAttendanceController::class, 'check'])->name('attendance.check');
        Route::get('/attendance/list/{training_id}', [TeacherAttendanceController::class, 'listPrevious'])->name('attendance.list');

        Route::post('/contents', [TeacherController::class, 'storeContent'])->name('contents.store');
        Route::put('/contents/{contentId}', [TeacherController::class, 'updateContent'])->name('contents.update');

        Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task_id}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task_id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::get('/tasks/{task_id}/submissions', [TaskController::class, 'submissions'])->name('tasks.submissions');
        Route::post('/submissions/{submission_id}/grade', [TaskController::class, 'grade'])->name('submissions.grade');

        Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
        Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/assessments/{assessment_id}', [AssessmentController::class, 'showAssessment'])->name('assessments.show');
        Route::get('/assessments/training/{training_id}', [AssessmentController::class, 'show'])->name('assessments.manage');
        Route::put('/assessments/{assessment_id}', [AssessmentController::class, 'update'])->name('assessments.update');
        Route::delete('/assessments/{assessment_id}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');

        Route::post('assessments/{assessment_id}/questions', [AssessmentController::class, 'addQuestion'])->name('assessments.questions.store');
        Route::put('questions/{question_id}/score', [AssessmentController::class, 'updateQuestionScore'])->name('questions.score.update');
        Route::put('questions/{question_id}', [AssessmentController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('questions/{question_id}', [AssessmentController::class, 'destroyQuestion'])->name('questions.destroy');
    });

Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:Student'])
    ->group(function () {
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
        Route::get('/calendar', [StudentCourseController::class, 'calendar'])->name('calendar');
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');

        Route::get('/courses', [StudentController::class, 'courses'])->name('courses.index');
        Route::get('/courses/{id}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{training}/content/{content}', [StudentCourseController::class, 'viewContent'])->name('courses.view-content');
        Route::post('/courses/{training}/content/{content}/complete', [StudentCourseController::class, 'markContentComplete'])->name('courses.content.complete');
        Route::get('/assessment/{id}/take', [StudentCourseController::class, 'takeExam'])->name('assessment.take');
        Route::get('/assessment/{id}/results', [StudentCourseController::class, 'showAssessmentResults'])->name('assessment.results');
        Route::post('/assessment/{id}/submit', [StudentCourseController::class, 'submitExam'])->name('assessment.submit');

        Route::post('/tasks/{task_id}/submit', [StudentCourseController::class, 'submitTask'])->name('tasks.submit');
        Route::get('/courses/{id}/certificate', [StudentCourseController::class, 'downloadCertificate'])->name('courses.certificate');
        Route::get('/courses/{id}/certificate/preview', [StudentCourseController::class, 'previewCertificate'])->name('courses.certificate.preview');
        Route::get('/notifications/unread', [StudentController::class, 'getUnreadNotifications'])->name('notifications.unread');
        Route::post('/notifications/mark-all-read', [StudentController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
        Route::post('/notifications/{notification_id}/mark-read', [StudentController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
    });

Route::get('/verify/certificate/{code}', [StudentCourseController::class, 'verifyCertificate'])->name('certificate.verify');
