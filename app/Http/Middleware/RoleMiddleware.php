<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $this->canAccessRole($user, $role)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }

    private function canAccessRole($user, string $role): bool
    {
        return $user->roles->contains('name', $role)
            || $this->isAdministratorManagingTeacherArea($user, $role);
    }

    private function isAdministratorManagingTeacherArea($user, string $role): bool
    {
        return $role === 'Teacher'
            && $user->roles->contains('name', 'Administrator');
    }
}
