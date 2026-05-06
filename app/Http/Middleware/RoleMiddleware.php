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

        // Si está autenticado pero sin el rol necesario
        if (!$user->roles->contains('name', $role)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}