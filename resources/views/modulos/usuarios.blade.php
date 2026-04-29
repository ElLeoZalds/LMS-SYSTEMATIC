@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="container mt-4">

    <h2>Gestión de Usuarios</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#usuarioModal">
        Crear Usuario
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align: center;">ID</th>
                <th style="text-align: center;">Persona</th>
                <th style="text-align: center;">Usuario</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $u)
            <tr>
                <td style="text-align: center;">{{ $u->idusuario }}</td>
                <td style="text-align: center;">{{ $u->persona_nombre }}</td>
                <td style="text-align: center;">{{ $u->username }}</td>
                <td style="text-align: center;">{{ $u->estado }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('usuarios.edit', $u->idusuario) }}" class="btn btn-primary btn-sm">Editar</a>

                    <form action="{{ route('usuarios.destroy', $u->idusuario) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
<div class="modal fade" id="usuarioModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>
                    {{ isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ isset($usuario)
        ? route('usuarios.update', $usuario->idusuario)
        : route('usuarios.store') }}">

                @csrf
                @if(isset($usuario))
                @method('PUT')
                @endif
                <div class="modal-body">
                    <select name="idpersona" class="form-control mb-2" required>
                        <option value="">Seleccione persona</option>
                        @foreach($personas as $p)
                        <option value="{{ $p->idpersona }}" {{ (isset($usuario) && $usuario->idpersona == $p->idpersona) ? 'selected' : '' }}>
                            {{ $p->nombres }} {{ $p->apellidos }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="username" class="form-control mb-2" placeholder="Username"
                        value="{{ old('username', $usuario->username ?? '') }}" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-primary">
                        {{ isset($usuario) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
@if(isset($usuario))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('usuarioModal')).show();
    });
</script>
@endif
@endpush