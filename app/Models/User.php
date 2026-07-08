<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'person_id',
        'username',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'teacher_specialties', 'user_id', 'specialty_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'teacher_id', 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'user_id');
    }

    public function administratedTrainings()
    {
        return $this->hasMany(Training::class, 'administrator_id', 'user_id');
    }

    public function administratedEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'administrator_id', 'user_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains(fn ($role) => strcasecmp((string) $role->name, $roleName) === 0);
    }

    public function hasMultipleRoles(): bool
    {
        return $this->roles->count() > 1;
    }

    public function getAvailableRoles(): \Illuminate\Support\Collection
    {
        return $this->roles;
    }

    public function isAdmin(): bool
    {
        return $this->roles->contains('name', 'Administrator');
    }

    public function isAdministrator(): bool
    {
        return $this->hasRole('Administrator');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('Teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('Student');
    }

    public function getEmailForVerification(): string
    {
        return $this->person?->email ?? $this->username;
    }
}
