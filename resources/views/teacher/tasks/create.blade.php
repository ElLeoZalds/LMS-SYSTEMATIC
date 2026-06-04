@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-3">
            <a href="{{ route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'tareas']) }}" class="text-decoration-none small">
                <i class="bi bi-arrow-left me-2"></i>Volver al Curso
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
                            <label for="delivery_date" class="form-label small fw-bold">Fecha de Entrega</label>
                            <input type="date" class="form-control form-control-sm" id="delivery_date" name="delivery_date"
                                value="{{ old('delivery_date') }}" required min="{{ now()->toDateString() }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
                            @error('delivery_date') <div class="text-danger small">{{ $message }}</div> @enderror
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