@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-3">
            <a href="{{ route('teacher.courses') }}" class="text-decoration-none small">
                <i class="bi bi-arrow-left me-2"></i>Volver a mis cursos
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <h1 class="h3 mb-4 text-gray-800">Crear Tarea - {{ $training->course->title }}</h1>

                <form action="{{ route('teacher.tasks.store') }}" method="POST" class="row g-3"> {{-- Grid con gap reducido
                    --}}
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">

                    {{-- Columna Izquierda --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label small fw-bold">Título de la Tarea</label> {{-- Label
                            pequeño --}}
                            <input type="text" class="form-control form-control-sm" id="title" name="title"
                                value="{{ old('title') }}" required>
                            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label small fw-bold">Fecha de Inicio (Opcional)</label>
                            <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                                value="{{ old('start_date', now()->toDateString()) }}">
                            @error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label small fw-bold">Fecha de Entrega</label>
                            <input type="date" class="form-control form-control-sm" id="end_date" name="end_date"
                                value="{{ old('end_date') }}" required>
                            @error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Columna Derecha --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label small fw-bold">Instrucciones (Descripción)</label>
                            <textarea class="form-control" id="description" name="description" rows="8"
                                placeholder="Describe las instrucciones detalladas de la tarea...">{{ old('description') }}</textarea>
                            {{-- Área grande --}}
                            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Botón Submit --}}
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Crear Tarea</button> {{-- Botón pequeño para
                        densidad --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection