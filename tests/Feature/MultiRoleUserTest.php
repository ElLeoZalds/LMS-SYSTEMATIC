<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MultiRoleUserTest extends TestCase
{
    public function test_user_can_report_multiple_roles_and_active_role_helpers(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('people');
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();

        $this->artisan('migrate:fresh', ['--force' => true]);

        $person = Person::create([
            'first_names' => 'Ana',
            'last_names' => 'Pérez',
            'email' => 'ana@example.com',
        ]);

        $user = User::create([
            'person_id' => $person->person_id,
            'username' => 'ana',
            'password' => bcrypt('secret123'),
            'status' => 'A',
        ]);

        $studentRole = Role::create(['name' => 'Student']);
        $teacherRole = Role::create(['name' => 'Teacher']);

        $user->roles()->attach([$studentRole->role_id, $teacherRole->role_id]);

        $user->load('roles');

        $this->assertTrue($user->hasMultipleRoles());
        $this->assertTrue($user->isStudent());
        $this->assertTrue($user->isTeacher());
        $this->assertSame(2, $user->getAvailableRoles()->count());
    }
}
