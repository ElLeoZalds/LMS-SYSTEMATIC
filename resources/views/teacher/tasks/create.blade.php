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
                <h1 class="h3 mb-4 text-gray-800">Crear Tarea - {{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h1>

                @if(($modules ?? collect())->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        Debe crear módulos para este curso antes de crear tareas.
                    </div>
                @endif

                <form action="{{ route('teacher.tasks.store') }}" method="POST" class="row g-3"> {{-- Grid con gap reducido
                    --}}
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">

                    {{-- Columna Izquierda --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="module_id" class="form-label small fw-bold">Módulo</label>
                            <select class="form-control form-control-sm" id="module_id" name="module_id" required>
                                <option value="">Selecciona un módulo</option>
                                @foreach($modules ?? [] as $module)
                                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                        {{ $module->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Selecciona el módulo al que pertenece esta tarea.</small>
                            @error('module_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

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
                        <button type="submit" class="btn btn-primary btn-sm" @if(($modules ?? collect())->isEmpty()) disabled @endif>Crear Tarea</button> {{-- Botón pequeño para
                        densidad --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection