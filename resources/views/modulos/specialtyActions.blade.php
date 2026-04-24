@extends('layouts.app')

@section('title', 'Gestión de Especialidades')

@section('content')
<div class="container mt-4">
    <h2>Gestión de Especialidades</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Botón para crear nueva especialidad -->
    @if(!isset($especialidad))
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#especialidadModal">
        Crear Especialidad
    </button>
    @endif

    <!-- Tabla de especialidades -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Especialidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($especialidades as $esp)
            <tr>
                <td>{{ $esp->idespecialidad }}</td>
                <td>{{ $esp->especialidad }}</td>
                <td>
                    <a href="{{ route('especialidades.edit', $esp->idespecialidad) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('especialidades.destroy', $esp->idespecialidad) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar esta especialidad?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No hay especialidades registradas</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal para crear/editar especialidad -->
    @if(isset($especialidad))
    <div class="modal fade show" id="especialidadModal" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Especialidad</h5>
                    <a href="{{ route('especialidades.index') }}" class="btn-close"></a>
                </div>
                <form action="{{ route('especialidades.update', $especialidad->idespecialidad) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Nombre de la Especialidad</label>
                            <input type="text" name="especialidad" id="especialidad" class="form-control" 
                                   value="{{ old('especialidad', $especialidad->especialidad) }}" required maxlength="100">
                            @error('especialidad')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @else
    <!-- Modal crear -->
    <div class="modal fade" id="especialidadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Especialidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('especialidades.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Nombre de la Especialidad</label>
                            <input type="text" name="especialidad" id="especialidad" class="form-control" 
                                   value="{{ old('especialidad') }}" required maxlength="100">
                            @error('especialidad')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection