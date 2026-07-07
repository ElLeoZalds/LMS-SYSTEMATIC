<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_profile_information_and_password(): void
    {
        $person = Person::create([
            'first_names' => 'Ana',
            'last_names' => 'García',
            'email' => 'ana@example.com',
            'phone' => '987654321',
            'document_number' => '12345678',
        ]);

        $role = Role::create(['name' => 'Student']);
        $user = User::factory()->create([
            'person_id' => $person->person_id,
            'username' => 'anagarcia',
            'password' => Hash::make('password123'),
        ]);
        $user->roles()->attach($role->role_id);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'first_names' => 'Anita',
            'last_names' => 'García Pérez',
            'email' => 'anita@example.com',
            'phone' => '912345678',
            'document_number' => '87654321',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $person->refresh();
        $this->assertSame('Anita', $person->first_names);
        $this->assertSame('García Pérez', $person->last_names);
        $this->assertSame('anita@example.com', $person->email);
        $this->assertSame('912345678', $person->phone);
        $this->assertSame('87654321', $person->document_number);

        $passwordResponse = $this->actingAs($user)->put(route('profile.password.update'), [
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $passwordResponse->assertRedirect(route('profile.edit'));
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_profile_accepts_peruvian_dni_and_phone_format(): void
    {
        $person = Person::create([
            'first_names' => 'Luis',
            'last_names' => 'Pérez',
            'email' => 'luis@example.com',
        ]);

        $role = Role::create(['name' => 'Student']);
        $user = User::factory()->create([
            'person_id' => $person->person_id,
            'username' => 'luisperez',
            'password' => Hash::make('password123'),
        ]);
        $user->roles()->attach($role->role_id);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'first_names' => 'Luis',
            'last_names' => 'Pérez',
            'email' => 'luis2@example.com',
            'phone' => '912345678',
            'document_number' => '87654321',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $person->refresh();
        $this->assertSame('912345678', $person->phone);
        $this->assertSame('87654321', $person->document_number);
    }
}
