@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Editar Curso"
            subtitle="Actualiza los datos del curso sin perder su configuración actual."
            action-route="{{ route('admin.courses.index') }}"
            action-label="Volver al listado"
            action-icon="arrow-left"
        />

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="title">Título</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="specialty_id">Especialidad</label>
                                <select name="specialty_id" id="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}" {{ old('specialty_id', $course->specialty_id) == $specialty->specialty_id ? 'selected' : '' }}>
                                            {{ $specialty->specialty }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="hours_count">Horas</label>
                                <input type="number" name="hours_count" id="hours_count" class="form-control @error('hours_count') is-invalid @enderror" value="{{ old('hours_count', $course->hours_count) }}" min="1" step="1">
                                @error('hours_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reference_price">Precio de referencia</label>
                                <input type="number" name="reference_price" id="reference_price" class="form-control @error('reference_price') is-invalid @enderror" value="{{ old('reference_price', $course->reference_price) }}" min="0" step="0.01" inputmode="decimal">
                                @error('reference_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="banner_path">Ruta del banner</label>
                                <input type="text" name="banner_path" id="banner_path" class="form-control @error('banner_path') is-invalid @enderror" value="{{ old('banner_path', $course->banner_path) }}">
                                @error('banner_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
