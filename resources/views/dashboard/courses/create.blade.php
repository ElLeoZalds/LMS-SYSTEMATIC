@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
    <div class="container mt-4">
        <h2>Create Course</h2>
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idespecialidad" class="form-label">Specialty</label>
                <select class="form-control" id="idespecialidad" name="idespecialidad" required>
                    <option value="">Select a specialty</option>
                    @foreach($especialidades as $esp)
                        <option value="{{ $esp->idespecialidad }}" {{ old('idespecialidad') == $esp->idespecialidad ? 'selected' : '' }}>{{ $esp->especialidad }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="titulo" class="form-label">Title</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Description</label>
                <textarea class="form-control" id="descripcion" name="descripcion"
                    required>{{ old('descripcion') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="cantidadhoras" class="form-label">Hours</label>
                <input type="number" class="form-control" id="cantidadhoras" name="cantidadhoras"
                    value="{{ old('cantidadhoras') }}" required>
            </div>
            <div class="mb-3">
                <label for="precioreferencial" class="form-label">Reference Price</label>
                <input type="number" step="0.01" class="form-control" id="precioreferencial" name="precioreferencial"
                    value="{{ old('precioreferencial') }}" required>
            </div>
            <div class="mb-3">
                <label for="pathbanner" class="form-label">Banner URL (optional)</label>
                <input type="text" class="form-control" id="pathbanner" name="pathbanner" value="{{ old('pathbanner') }}">
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection