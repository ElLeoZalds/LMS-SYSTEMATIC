<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\People;
use App\Models\Role;
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
                    $q->where('first_names', 'like', '%' . $request->search . '%')
                      ->orWhere('last_names', 'like', '%' . $request->search . '%')
                      ->orWhere('document_number', 'like', '%' . $request->search . '%');
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
        $request->validate([
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

        $user = DB::transaction(function () use ($request) {
            $person = People::create([
                'first_names' => $request->first_names,
                'last_names' => $request->last_names,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
            ]);

            $user = User::create([
                'person_id' => $person->person_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status' => $request->status ?? 'A',
            ]);

            $user->roles()->attach($request->role_id);

            return $user;
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = User::with('person', 'roles')->findOrFail($id);

        $request->validate([
            'first_names' => 'required|string|max:20',
            'last_names' => 'required|string|max:20',
            'document_type' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:20|unique:people,document_number,' . $user->person_id . ',person_id',
            'email' => 'required|email|max:150|unique:people,email,' . $user->person_id . ',person_id',
            'phone' => 'nullable|string|max:9',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'birth_date' => 'nullable|date',
            'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'sometimes|in:A,I',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->person->update([
                'first_names' => $request->first_names,
                'last_names' => $request->last_names,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
            ]);

            $userData = [
                'username' => $request->username,
                'status' => $request->status ?? 'A',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $user->roles()->sync([$request->role_id]);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::with('person')->findOrFail($id);

        DB::transaction(function () use ($user) {
            $personId = $user->person_id;
            $user->delete();
            People::where('person_id', $personId)->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}