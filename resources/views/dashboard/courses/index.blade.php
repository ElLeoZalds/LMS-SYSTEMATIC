@extends('layouts.app')

@section('title', 'Listado de Cursos')

@section('content')
    <div class="container mt-4">
        <h2>Listado de Cursos</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('cursos.create') }}" class="btn btn-success mb-3">Crear Curso</a>
        <a href="{{ route('cursos.reporte') }}" class="btn btn-danger mb-3">
            <i class="fa fa-file-pdf"></i> Generar Reporte PDF
        </a></thead>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Horas</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->idcurso }}</td>
                        <td>{{ $course->titulo }}</td>
                        <td>{{ $course->descripcion }}</td>
                        <td>{{ $course->cantidadhoras }}</td>
                        <td>S/ {{ $course->precioreferencial }}</td>
                        <td>
                            <a href="{{ route('cursos.edit', $course->idcurso) }}" class="btn btn-primary btn-sm">Editar</a>
                            <a href="{{ route('cursos.matriculas', $course->idcurso) }}" class="btn btn-success btn-sm">Agregar
                                estudiante</a>
                            <form action="{{ route('cursos.destroy', $course->idcurso) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Seguro de eliminar este curso?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection