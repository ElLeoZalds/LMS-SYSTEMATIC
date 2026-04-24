@extends('layouts.app')

@section('title', 'Crear Curso')

@section('content')
<div class="container mt-4">
    <h2>Crear Curso</h2>
    <form action="{{ route('cursos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="idespecialidad" class="form-label">Especialidad</label>
            <select class="form-control" id="idespecialidad" name="idespecialidad" required>
                <option value="">Seleccione una especialidad</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp->idespecialidad }}" {{ old('idespecialidad') == $esp->idespecialidad ? 'selected' : '' }}>{{ $esp->especialidad }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required>{{ old('descripcion') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="cantidadhoras" class="form-label">Cantidad de Horas</label>
            <input type="number" class="form-control" id="cantidadhoras" name="cantidadhoras" value="{{ old('cantidadhoras') }}" required>
        </div>
        <div class="mb-3">
            <label for="precioreferencial" class="form-label">Precio Referencial</label>
            <input type="number" step="0.01" class="form-control" id="precioreferencial" name="precioreferencial" value="{{ old('precioreferencial') }}" required>
        </div>
        <div class="mb-3">
            <label for="pathbanner" class="form-label">URL del Banner (opcional)</label>
            <input type="text" class="form-control" id="pathbanner" name="pathbanner" value="{{ old('pathbanner') }}">
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
