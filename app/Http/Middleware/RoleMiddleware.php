<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();

        // Si no está autenticado, redirigir a login
        if (!$user) {
            return redirect()->route('login');
        }

        $hasRole = $user->roles->contains('name', $role);
        $isAdministratorManagingTeacherArea = $role === 'Teacher'
            && $user->roles->contains('name', 'Administrator');

        // Si está autenticado pero sin el rol necesario
        if (!$hasRole && !$isAdministratorManagingTeacherArea) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
