@extends('layouts.app')

@section('title', 'Gestión de Especialidades')

@section('content')
<div class="container mt-4">
    <h2>Gestión de Especialidades</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- BOTÓN CREAR -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#especialidadModal">
        Crear Especialidad
    </button>

    <!-- TABLA -->
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
                    <a href="{{ route('especialidades.edit', $esp->idespecialidad) }}" class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <form action="{{ route('especialidades.destroy', $esp->idespecialidad) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No hay datos</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<!-- MODAL (crear + editar) -->
<div class="modal fade" id="especialidadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ isset($especialidad) ? 'Editar Especialidad' : 'Nueva Especialidad' }}
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST"
                action="{{ isset($especialidad)
                    ? route('especialidades.update', $especialidad->idespecialidad)
                    : route('especialidades.store') }}">

                @csrf
                @if(isset($especialidad))
                    @method('PUT')
                @endif

                <div class="modal-body">
                    <input type="text" name="especialidad" class="form-control"
                        value="{{ old('especialidad', $especialidad->especialidad ?? '') }}"
                        placeholder="Nombre especialidad" required>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-primary">
                        {{ isset($especialidad) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@push('scripts')
@if(isset($especialidad))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('especialidadModal'));
        modal.show();
    });
</script>
@endif
@endpush