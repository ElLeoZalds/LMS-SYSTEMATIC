<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['person', 'roles'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('person', function ($q) use ($request) {
                    $q->where('first_names', 'like', '%'.$request->search.'%')
                        ->orWhere('last_names', 'like', '%'.$request->search.'%')
                        ->orWhere('document_number', 'like', '%'.$request->search.'%');
                });
            })
            ->when($request->role, function ($query) use ($request) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            })
            ->when($request->year, function ($query) use ($request) {
                $query->whereHas('person', function ($q) use ($request) {
                    $q->whereYear('birth_date', $request->year);
                });
            });

        $users = $query->get();

        $roles = Role::all();
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 5);

        return view('admin.users.index', compact('users', 'roles', 'years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_names' => 'required|string|max:20',
            'last_names' => 'required|string|max:20',
            'document_type' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:20|unique:people,document_number',
            'email' => 'required|email|max:150|unique:people,email',
            'phone' => 'nullable|string|max:9',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'sometimes|in:A,I',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        DB::transaction(fn () => $this->createUserWithPerson($data));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = User::with('person', 'roles')->findOrFail($id);

        $data = $request->validate([
            'first_names' => 'required|string|max:20',
            'last_names' => 'required|string|max:20',
            'document_type' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:20|unique:people,document_number,'.$user->person_id.',person_id',
            'email' => 'required|email|max:150|unique:people,email,'.$user->person_id.',person_id',
            'phone' => 'nullable|string|max:9',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date',
            'username' => 'required|string|max:50|unique:users,username,'.$user->user_id.',user_id',
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'sometimes|in:A,I',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        DB::transaction(fn () => $this->updateUserWithPerson($user, $data));

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::with('person')->findOrFail($id);

        DB::transaction(function () use ($user) {
            $personId = $user->person_id;
            $user->delete();
            Person::where('person_id', $personId)->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    private function createUserWithPerson(array $data): User
    {
        $person = Person::create($this->personData($data));

        $user = User::create($this->userData($data, $person->person_id));
        $user->roles()->attach($data['role_id']);

        return $user;
    }

    private function updateUserWithPerson(User $user, array $data): void
    {
        $user->person->update($this->personData($data));
        $user->update($this->userData($data));
        $user->roles()->sync([$data['role_id']]);
    }

    private function personData(array $data): array
    {
        return [
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
            'document_type' => $data['document_type'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'gender' => $data['gender'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
        ];
    }

    private function userData(array $data, ?int $personId = null): array
    {
        $userData = [
            'username' => $data['username'],
            'status' => $data['status'] ?? 'A',
        ];

        if ($personId) {
            $userData['person_id'] = $personId;
        }

        if (! empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        return $userData;
    }
}
