<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        $person = Person::create([
            'first_names' => fake()->firstName(),
            'last_names' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('9########'),
            'document_number' => fake()->numerify('########'),
        ]);

        return [
            'person_id' => $person->person_id,
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password123'),
            'status' => 'A',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => 'I',
        ]);
    }
}
