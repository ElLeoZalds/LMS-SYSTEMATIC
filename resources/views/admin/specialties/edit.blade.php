@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Editar Especialidad"
            subtitle="Actualiza la información de la especialidad seleccionada."
            action-route="{{ route('admin.specialties.index') }}"
            action-label="Volver al listado"
            action-icon="arrow-left"
        />

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.specialties.update', $specialty) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="specialty">Especialidad</label>
                        <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty', $specialty->specialty) }}" required>
                        @error('specialty')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('admin.specialties.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
