<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Person;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentEnrollmentScopeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('specialties');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('people');
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();

        Artisan::call('migrate:fresh', ['--force' => true]);
    }

    public function test_student_dashboard_and_courses_only_show_enrolled_trainings(): void
    {
        $studentPerson = Person::create([
            'first_names' => 'Carlos',
            'last_names' => 'Rojas',
            'email' => 'carlos@example.com',
        ]);

        $student = User::create([
            'person_id' => $studentPerson->person_id,
            'username' => 'carlos',
            'password' => bcrypt('secret123'),
            'status' => 'A',
        ]);

        $studentRole = Role::create(['name' => 'Student']);
        $teacherRole = Role::create(['name' => 'Teacher']);
        $student->roles()->attach([$studentRole->role_id, $teacherRole->role_id]);

        $specialty = Specialty::create([
            'specialty' => 'Tecnología',
            'is_active' => true,
        ]);

        $course = Course::create([
            'specialty_id' => $specialty->specialty_id,
            'title' => 'Curso de Laravel',
            'abbreviation' => 'LAR',
            'description' => 'Curso',
            'hours_count' => 20,
            'reference_price' => 100,
            'is_active' => true,
        ]);

        $taughtTraining = Training::create([
            'course_id' => $course->course_id,
            'teacher_id' => $student->user_id,
            'administrator_id' => $student->user_id,
            'code' => 'TUGHT-001',
            'modality' => 'virtual',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 1,
            'is_active' => true,
        ]);

        $enrolledTraining = Training::create([
            'course_id' => $course->course_id,
            'teacher_id' => $student->user_id,
            'administrator_id' => $student->user_id,
            'code' => 'ENROLLED-001',
            'modality' => 'virtual',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 1,
            'is_active' => true,
        ]);

        Enrollment::create([
            'training_id' => $enrolledTraining->training_id,
            'student_id' => $student->user_id,
            'administrator_id' => $student->user_id,
            'enrollment_date' => now()->toDateString(),
            'scholarship_percentage' => 0,
            'status' => 'A',
        ]);

        $this->withSession([
            'active_role_id' => $studentRole->role_id,
            'active_role_name' => $studentRole->name,
        ]);
        $this->actingAs($student);

        $dashboardResponse = $this->get(route('student.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertViewHas('activeTrainings', function ($activeTrainings) use ($enrolledTraining, $taughtTraining) {
            return $activeTrainings->contains('training_id', $enrolledTraining->training_id)
                && ! $activeTrainings->contains('training_id', $taughtTraining->training_id);
        });

        $coursesResponse = $this->get(route('student.courses.index'));
        $coursesResponse->assertOk();
        $coursesResponse->assertViewHas('courses', function ($courses) use ($enrolledTraining, $taughtTraining) {
            return $courses->contains('training_id', $enrolledTraining->training_id)
                && ! $courses->contains('training_id', $taughtTraining->training_id);
        });
    }
}
