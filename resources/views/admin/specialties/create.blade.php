@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Crear Especialidad"
            subtitle="Define una nueva especialidad para organizar los cursos del panel administrativo."
            action-route="{{ route('admin.specialties.index') }}"
            action-label="Volver al listado"
            action-icon="arrow-left"
        />

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.specialties.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="specialty">Especialidad</label>
                        <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty') }}" required>
                        @error('specialty')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('admin.specialties.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
