<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:people,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        [$firstName, $lastName] = $this->splitFullName($data['full_name']);

        try {
            DB::transaction(fn () => $this->createStudentAccount($data, $firstName, $lastName));

            return redirect()->route('login')
                ->with('success', '¡Registro completado con éxito! Ahora puedes iniciar sesión.');
        } catch (Throwable) {
            return back()->withInput()->withErrors([
                'email' => 'Ocurrió un error al procesar el registro. Inténtelo de nuevo.',
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials['status'] = 'A';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectAuthenticatedUser();
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden o el usuario se encuentra inactivo.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function splitFullName(string $fullName): array
    {
        $nameParts = preg_split('/\s+/', trim($fullName));
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $firstName = implode(' ', $nameParts);

        return [$firstName, $lastName];
    }

    private function createStudentAccount(array $data, string $firstName, string $lastName): void
    {
        $person = Person::create([
            'first_names' => $firstName,
            'last_names' => $lastName,
            'email' => $data['email'],
        ]);

        $user = User::create([
            'person_id' => $person->person_id,
            'username' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'A',
        ]);

        $studentRole = Role::where('name', 'Student')->firstOrFail();

        $user->roles()->attach($studentRole->role_id);
    }

    private function redirectAuthenticatedUser()
    {
        $roles = Auth::user()->roles;

        if ($roles->contains('name', 'Administrator')) {
            return redirect()->route('admin.dashboard');
        }

        if ($roles->contains('name', 'Teacher')) {
            return redirect()->route('teacher.dashboard');
        }

        if ($roles->contains('name', 'Student')) {
            return redirect()->route('student.dashboard');
        }

        Auth::logout();

        return back()->withErrors([
            'username' => 'Usuario sin rol válido asignado. Contacte al administrador.',
        ]);
    }
}
