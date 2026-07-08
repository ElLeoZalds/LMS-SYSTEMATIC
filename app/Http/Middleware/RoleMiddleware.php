<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        Log::info('RoleMiddleware', [
            'required_role' => $role,
            'active_role_id' => session('active_role_id'),
            'active_role_name' => session('active_role_name'),
            'user_roles' => $user->roles->pluck('name')->toArray(),
        ]);

        $requiredRoleName = $role;
        $userHasRole = $user->roles->contains('name', $requiredRoleName);

        if (! $userHasRole) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        if ($user->roles->count() > 1) {
            $activeRoleId = session('active_role_id');
            $activeRole = $user->roles->firstWhere('role_id', $activeRoleId);

            if (! $activeRole || $activeRole->name !== $requiredRoleName) {
                session(['show_role_modal' => true]);

                return $next($request);
            }

            session(['active_role_id' => $activeRole->role_id, 'active_role_name' => $activeRole->name, 'show_role_modal' => false]);
        } else {
            $singleRole = $user->roles->first();
            session(['active_role_id' => $singleRole->role_id, 'active_role_name' => $singleRole->name, 'show_role_modal' => false]);
        }

        return $next($request);
    }

    private function canAccessRole($user, string $role): bool
    {
        return $user->hasRole($role)
            || $this->isAdministratorManagingTeacherArea($user, $role);
    }

    private function isAdministratorManagingTeacherArea($user, string $role): bool
    {
        return $role === 'Teacher'
            && $user->isAdministrator();
    }
}
