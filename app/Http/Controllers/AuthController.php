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

        // Transacción para asegurar la integridad de datos
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $person = People::create([
                'first_names' => $firstName,
                'last_names' => $lastName,
                'email' => $request->email,
            ]);

            $user = User::create([
                'person_id' => $person->person_id,
                'username' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'status' => 'A',
            ]);

            $studentRole = Role::where('name', 'Student')->first();
            if (!$studentRole) {
                throw new \Exception('El rol "Student" no está configurado en el sistema. Contacte al administrador.');
            }
            $user->roles()->attach($studentRole->role_id);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('login')
                ->with('success', '¡Registro completado con éxito! Ahora puedes iniciar sesión.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->withErrors([
                'email' => 'Ocurrió un error al procesar el registro. Inténtelo de nuevo.'
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Forzamos que solo entren usuarios activos
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status' => 'A'
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Mapeo seguro con colecciones para evitar errores multi-rol
            if ($user->roles->contains('name', 'Administrator')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->roles->contains('name', 'Teacher')) {
                return redirect()->route('teacher.dashboard');
            }

            if ($user->roles->contains('name', 'Student')) {
                return redirect()->route('student.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'username' => 'Usuario sin rol válido asignado. Contacte al administrador.'
            ]);
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden o el usuario se encuentra inactivo.'
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