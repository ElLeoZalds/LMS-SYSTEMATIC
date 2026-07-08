<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Course;
use App\Models\Module;
use App\Models\Person;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_content_with_video_url_and_delete_it(): void
    {
        $teacherRole = Role::create(['name' => 'Teacher']);
        $teacherPerson = Person::create([
            'first_names' => 'Ana',
            'last_names' => 'García',
            'email' => 'ana@example.com',
            'phone' => '987654321',
            'document_number' => '11111111',
        ]);
        $teacher = User::create([
            'person_id' => $teacherPerson->person_id,
            'username' => 'teachercontent',
            'password' => bcrypt('password123'),
            'status' => 'A',
        ]);
        $teacher->roles()->attach($teacherRole->role_id);

        $specialty = Specialty::create([
            'specialty' => 'Tecnología',
            'is_active' => true,
        ]);
        $course = Course::create([
            'specialty_id' => $specialty->specialty_id,
            'title' => 'Curso de Prueba',
            'abbreviation' => 'CPP',
            'description' => 'Curso de pruebas',
            'hours_count' => 20,
            'reference_price' => 100,
            'is_active' => true,
        ]);
        $module = Module::create([
            'course_id' => $course->course_id,
            'title' => 'Módulo de pruebas',
            'description' => 'Módulo',
            'order' => 1,
            'is_active' => true,
        ]);
        $training = Training::create([
            'course_id' => $course->course_id,
            'teacher_id' => $teacher->user_id,
            'administrator_id' => $teacher->user_id,
            'code' => 'TRAIN-001',
            'modality' => 'virtual',
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'status' => Training::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $response = $this->actingAs($teacher)->post(route('teacher.contents.store'), [
            'training_id' => $training->training_id,
            'module_id' => $module->id,
            'title' => 'Video introductorio',
            'description' => 'Introducción',
            'content_type' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=abc123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $content = Content::where('training_id', $training->training_id)->latest('content_id')->first();
        $this->assertNotNull($content);
        $this->assertSame('video', $content->type);
        $this->assertSame('https://www.youtube.com/watch?v=abc123', $content->video_url);

        $deleteResponse = $this->actingAs($teacher)->delete(route('teacher.contents.destroy', $content->content_id));

        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('contents', ['content_id' => $content->content_id]);
    }
}
