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
            'first_names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => 'required|email|unique:people,email',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'accepted',
        ]);

        try {
            $user = DB::transaction(fn () => $this->createStudentAccount($data));

            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectAuthenticatedUser('¡Registro completado con éxito! Bienvenido a la plataforma.');
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

    private function createStudentAccount(array $data): User
    {
        $person = Person::create([
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
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

        return $user;
    }

    public function showVerificationNotice()
    {
        return redirect()->route('login')->with('success', 'Verifica tu correo para completar tu cuenta.');
    }

    public function verifyEmail(Request $request, int $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);

        return redirect()->route('student.dashboard')
            ->with('success', 'Tu correo electrónico ha sido verificado correctamente.');
    }

    private function redirectAuthenticatedUser(?string $message = null)
    {
        $roles = Auth::user()->roles;

        if ($roles->contains('name', 'Administrator')) {
            $response = redirect()->route('admin.dashboard');
        } elseif ($roles->contains('name', 'Teacher')) {
            $response = redirect()->route('teacher.dashboard');
        } elseif ($roles->contains('name', 'Student')) {
            $response = redirect()->route('student.dashboard');
        } else {
            Auth::logout();

            return back()->withErrors([
                'username' => 'Usuario sin rol válido asignado. Contacte al administrador.',
            ]);
        }

        if ($message) {
            return $response->with('success', $message);
        }

        return $response;
    }
}
