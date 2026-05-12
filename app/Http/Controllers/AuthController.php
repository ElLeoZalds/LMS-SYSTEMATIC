<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\People;
use App\Models\Role;
use App\Models\User;

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
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:people,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $fullName = trim($request->full_name);
        $nameParts = preg_split('/\s+/', $fullName);
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $firstName = implode(' ', $nameParts);

        $person = People::create([
            'first_names' => $firstName,
            'last_names' => $lastName,
            'email' => $request->email,
        ]);

        $user = User::create([
            'person_id' => $person->person_id,
            'username' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'A',
        ]);

        $studentRole = Role::where('name', 'Student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->role_id);
        }

        return redirect()->route('login')->with('success', '¡Registro completado con éxito! Ahora puedes iniciar sesión');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = optional($user->roles->first())->name;

            if (!$role) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Usuario sin rol asignado. Contacte al administrador.'
                ]);
            }

            if ($role === 'Administrator') {
                return redirect()->route('admin.dashboard');
            }

            if ($role === 'Teacher') {
                return redirect()->route('teacher.dashboard');
            }

            if ($role === 'Student') {
                return redirect()->route('student.dashboard');
            }

            Auth::logout();
        }

        return back()->withErrors([
            'username' => 'Credenciales incorrectas'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}