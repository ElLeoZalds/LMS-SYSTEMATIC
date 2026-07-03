<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase as BaseTestCase;

class AuthRoleConsistencyTest extends BaseTestCase
{
    public function test_user_role_helpers_respect_loaded_roles(): void
    {
        $user = new User();
        $user->setRelation('roles', collect([
            new Role(['name' => 'Administrator']),
        ]));

        $this->assertTrue($user->hasRole('Administrator'));
        $this->assertFalse($user->hasRole('Student'));
        $this->assertTrue($user->isAdministrator());
        $this->assertFalse($user->isStudent());
    }
}
