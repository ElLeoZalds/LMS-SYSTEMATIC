@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-1">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Crear Horario</h1>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="training_id" class="form-label">Capacitación</label>
                        <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                            <option value="">Seleccionar capacitación</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->training_id }}" {{ old('training_id') == $training->training_id ? 'selected' : '' }}>
                                    {{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}
                                    @if(optional($training->teacher->person)->first_names)
                                        - {{ optional($training->teacher->person)->first_names }} {{ optional($training->teacher->person)->last_names }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('training_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Hora inicio</label>
                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="end_time" class="form-label">Hora fin</label>
                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
