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
        return [
            'person_id' => Person::query()->inRandomOrder()->value('person_id') ?? 1,
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
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
