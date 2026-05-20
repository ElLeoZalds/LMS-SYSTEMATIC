@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <a href="{{ route('teacher.courses') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i>Volver a mis cursos
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <h1 class="h3 mb-4 text-gray-800">Tomar Asistencia</h1>

                <form action="{{ route('teacher.attendance.create') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="training_id" class="form-label fw-bold small text-muted">Capacitación</label>
                        @if(isset($training))
                            <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                            <input type="text" id="training_id" class="form-control" value="{{ $training->course->title }}" readonly>
                        @else
                            <select id="training_id" name="training_id" class="form-select" required>
                                <option value="">Seleccione una capacitación</option>
                                @foreach($trainings ?? [] as $item)
                                    <option value="{{ $item->training_id }}">{{ $item->course->title }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label for="attendance_date" class="form-label fw-bold small text-muted">Fecha de Asistencia</label>
                        <input type="date" id="attendance_date" name="date" class="form-control" value="{{ $date ?? date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Buscar Alumnos</button>
                    </div>
                </form>

                @if(!isset($training))
                    <div class="alert alert-info mb-4">
                        Selecciona una capacitación y una fecha para cargar el listado de alumnos.
                    </div>
                @endif

                @if(isset($training))
                    <form action="{{ route('teacher.attendance.store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                        <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

                        <div class="row align-items-center mb-4">
                            <div class="col-md-8">
                                <h2 class="h5 mb-0">{{ $training->course->title }}</h2>
                                <p class="text-muted small mb-0">Fecha de asistencia: {{ $date ?? date('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <button type="button" class="btn btn-outline-success" onclick="markAllPresent()">
                                    <i class="bi bi-check2-all me-1"></i> Marcar Todos como Presentes
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-dark small fw-bold">Estudiante</th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-success">✓ Presente</span></th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-danger">✕ Ausente</span></th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-warning">⊘ Justificado</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($training->enrollments as $enrollment)
                                        <tr>
                                            <td class="align-middle">
                                                <div class="text-dark">
                                                    {{ optional($enrollment->student->person)->first_names }}
                                                    {{ optional($enrollment->student->person)->last_names }}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="radio" name="attendances[{{ $loop->index }}][status]" value="P" class="form-check-input" checked>
                                                <input type="hidden" name="attendances[{{ $loop->index }}][student_id]" value="{{ $enrollment->student_id }}">
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="radio" name="attendances[{{ $loop->index }}][status]" value="A" class="form-check-input">
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="radio" name="attendances[{{ $loop->index }}][status]" value="J" class="form-check-input">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Guardar Asistencia
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <script>
            function markAllPresent() {
                var radios = document.querySelectorAll('input[type="radio"][value="P"]');
                radios.forEach(function(radio) {
                    radio.checked = true;
                });
            }
        </script>
@endsection