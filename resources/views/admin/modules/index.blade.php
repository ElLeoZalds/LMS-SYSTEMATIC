@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800">Módulos</h1>
            <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Nuevo Módulo
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.modules.index') }}" class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="course_id" class="form-label">Filtrar por curso</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_id }}" {{ request('course_id') == $course->course_id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary btn-block">Limpiar</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($modules as $module)
                            <tr>
                                <td>{{ $module->order }}</td>
                                <td>{{ $module->title }}</td>
                                <td>{{ $module->course->title ?? 'Sin curso' }}</td>
                                <td>
                                    @if($module->is_active)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.modules.toggle-active', $module) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-toggle-on"></i> {{ $module->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay módulos registrados.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
