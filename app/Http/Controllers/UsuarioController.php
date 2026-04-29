<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('usuarios as u')
            ->join('personas as p', 'u.idpersona', '=', 'p.idpersona')
            ->select('u.*', 'p.nombres as persona_nombre')
            ->get();

        // traer personas para el select
        $personas = DB::table('personas')->get();

        return view('modulos.usuarios', compact('usuarios', 'personas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpersona' => 'required|integer',
            'username' => 'required|max:50|unique:usuarios,username',
            'password' => 'required|min:6'
        ]);

        Usuario::create([
            'idpersona' => $request->idpersona,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'estado' => 'A',
            'fechacreacion' => now(),
            'fechamodificacion' => now()
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        $usuarios = DB::table('usuarios as u')
            ->join('personas as p', 'u.idpersona', '=', 'p.idpersona')
            ->select('u.*', 'p.nombres as persona_nombre')
            ->get();

        $personas = DB::table('personas')->get();

        return view('modulos.usuarios', compact('usuario', 'usuarios', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idpersona' => 'required|integer',
            'username' => 'required|max:50|unique:usuarios,username,' . $id . ',idusuario',
            'password' => 'nullable|min:6'
        ]);

        $usuario = Usuario::findOrFail($id);

        $data = [
            'idpersona' => $request->idpersona,
            'username' => $request->username,
            'fechamodificacion' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado');
    }
}
