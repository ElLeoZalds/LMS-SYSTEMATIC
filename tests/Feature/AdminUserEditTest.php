<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_another_user_profile_and_role(): void
    {
        $adminPerson = Person::create([
            'first_names' => 'Admin',
            'last_names' => 'User',
            'email' => 'admin@example.com',
        ]);

        $adminRole = Role::create(['name' => 'Administrator']);
        $adminUser = User::create([
            'person_id' => $adminPerson->person_id,
            'username' => 'adminuser',
            'password' => Hash::make('password123'),
            'status' => 'A',
        ]);
        $adminUser->roles()->attach($adminRole->role_id);

        $targetPerson = Person::create([
            'first_names' => 'Teacher',
            'last_names' => 'Example',
            'email' => 'teacher@example.com',
        ]);

        $teacherRole = Role::create(['name' => 'Teacher']);
        $targetUser = User::create([
            'person_id' => $targetPerson->person_id,
            'username' => 'targetteacher',
            'password' => Hash::make('password123'),
            'status' => 'A',
        ]);
        $targetUser->roles()->attach($teacherRole->role_id);

        $response = $this->actingAs($adminUser)->put(route('admin.users.update', $targetUser->user_id), [
            'first_names' => 'Updated',
            'last_names' => 'Teacher',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'role_ids' => [$adminRole->role_id],
            'status' => 'I',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $targetPerson->refresh();
        $this->assertSame('Updated', $targetPerson->first_names);
        $this->assertSame('Teacher', $targetPerson->last_names);
        $this->assertSame('updated@example.com', $targetPerson->email);

        $targetUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $targetUser->password));
        $this->assertSame('I', $targetUser->status);
        $this->assertTrue($targetUser->roles()->where('roles.role_id', $adminRole->role_id)->exists());
    }
}
