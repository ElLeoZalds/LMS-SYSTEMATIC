<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
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