<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:6',
        ]);

        $usuario = Usuario::where('username', $request->input('username'))->first();

        if (! $usuario || ! Hash::check($request->input('password'), $usuario->password) || $usuario->estado !== 'A') {
            return back()->withInput()->with('error', 'Usuario o contraseña incorrecta');
        }

        $role = $usuario->roles()->first()?->nombre;

        $request->session()->put([
            'usuario_id' => $usuario->idusuario,
            'username' => $usuario->username,
            'user_role' => $role ? strtolower($role) : 'estudiante',
            'logged_in' => true,
        ]);

        return $this->redirectByRole($role);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login')->with('info', 'Sesión cerrada correctamente');
    }

    protected function redirectByRole(?string $role)
    {
        return match (strtolower($role ?? 'estudiante')) {
            'administrador' => redirect()->route('dashboard.admin'),
            'docente' => redirect()->route('dashboard.teacher'),
            default => redirect()->route('dashboard.student'),
        };
    }
}
