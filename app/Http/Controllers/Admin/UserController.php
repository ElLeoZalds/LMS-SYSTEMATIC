<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchName = trim((string) $request->input('search_name', ''));
        $searchEmail = trim((string) $request->input('search_email', ''));
        $roleFilter = $request->input('role_filter');
        $statusFilter = $request->input('status_filter');

        $query = User::with(['person', 'roles'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Administrator', 'Teacher']);
            })
            ->when($searchName !== '', function ($query) use ($searchName) {
                $query->whereHas('person', function ($q) use ($searchName) {
                    $q->where('first_names', 'like', '%'.$searchName.'%')
                        ->orWhere('last_names', 'like', '%'.$searchName.'%');
                });
            })
            ->when($searchEmail !== '', function ($query) use ($searchEmail) {
                $query->whereHas('person', function ($q) use ($searchEmail) {
                    $q->where('email', 'like', '%'.$searchEmail.'%');
                });
            })
            ->when(in_array($roleFilter, ['Administrator', 'Teacher'], true), function ($query) use ($roleFilter) {
                $query->whereHas('roles', function ($q) use ($roleFilter) {
                    $q->where('name', $roleFilter);
                });
            })
            ->when(in_array($statusFilter, ['A', 'I'], true), function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderByDesc('user_id');

        $users = $query->paginate(10, ['*'], 'users_page')->appends([
            'search_name' => $searchName,
            'search_email' => $searchEmail,
            'role_filter' => $roleFilter,
            'status_filter' => $statusFilter,
        ]);

        $administratorsCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'Administrator');
        })->count();

        $teachersCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'Teacher');
        })->count();

        $roles = $this->allowedRoles();

        return view('admin.users.index', compact(
            'users',
            'searchName',
            'searchEmail',
            'roleFilter',
            'statusFilter',
            'administratorsCount',
            'teachersCount',
            'roles'
        ));
    }

    public function edit(User $user)
    {
        $user->load(['person', 'roles']);

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->allowedRoles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:150', Rule::unique('people', 'email')->ignore($user->person?->person_id, 'person_id')],
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:A,I',
            'role_ids' => ['required', 'array', 'min:1'],
            'role_ids.*' => ['required', Rule::in($this->allowedRoleIds())],
        ]);

        if ($this->shouldPreventStatusChange($user, $data['status'] ?? $user->status)) {
            return back()->withErrors([
                'status' => 'No se puede desactivar al último administrador activo ni al usuario autenticado.',
            ])->withInput();
        }

        DB::transaction(function () use ($user, $data) {
            $user->person->update([
                'first_names' => $data['first_names'],
                'last_names' => $data['last_names'],
                'email' => $data['email'],
            ]);

            $userData = [
                'status' => $data['status'] ?? $user->status,
            ];

            if (! empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $user->update($userData);
            $user->roles()->sync($data['role_ids']);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($this->shouldPreventStatusChange($user, 'I')) {
            return back()->withErrors([
                'status' => 'No se puede desactivar al último administrador activo ni al usuario autenticado.',
            ]);
        }

        $user->update(['status' => 'I']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }

    private function usersForRole(string $roleName, string $searchName, string $searchEmail, $page = null)
    {
        $query = User::with(['person', 'roles'])
            ->whereHas('roles', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })
            ->when($searchName !== '', function ($query) use ($searchName) {
                $query->whereHas('person', function ($q) use ($searchName) {
                    $q->where('first_names', 'like', '%'.$searchName.'%')
                        ->orWhere('last_names', 'like', '%'.$searchName.'%');
                });
            })
            ->when($searchEmail !== '', function ($query) use ($searchEmail) {
                $query->whereHas('person', function ($q) use ($searchEmail) {
                    $q->where('email', 'like', '%'.$searchEmail.'%');
                });
            })
            ->orderByDesc('user_id');

        $pageName = $roleName === 'Administrator' ? 'admin_page' : 'teacher_page';

        return $query->paginate(10, ['*'], $pageName, $page)->appends([
            'search_name' => $searchName,
            'search_email' => $searchEmail,
        ]);
    }

    private function allowedRoles()
    {
        return Role::whereIn('name', ['Administrator', 'Teacher', 'Student'])->get();
    }

    private function allowedRoleIds(): array
    {
        return $this->allowedRoles()->pluck('role_id')->map(fn ($id) => (int) $id)->all();
    }

    private function splitFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];

        if (count($parts) <= 1) {
            return [$parts[0] ?? '', ''];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [$firstName, $lastName];
    }

    private function shouldPreventStatusChange(User $user, string $targetStatus): bool
    {
        if ($targetStatus !== 'I') {
            return false;
        }

        if (Auth::id() === $user->user_id) {
            return true;
        }

        $isAdministrator = $user->roles()->where('name', 'Administrator')->exists();

        if (! $isAdministrator) {
            return false;
        }

        $activeAdministrators = User::where('status', 'A')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Administrator');
            })
            ->count();

        return $activeAdministrators <= 1;
    }
}
