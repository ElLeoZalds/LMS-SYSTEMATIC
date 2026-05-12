@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $training->course->title }}</h1>
                <p class="text-muted mb-0">Capacitación del curso con fecha de inicio y fin, y evaluaciones disponibles.</p>
            </div>
            <div class="text-end">
                <a href="{{ route('student.courses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle me-1"></i>Volver a mis cursos
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Detalles de la capacitación</h5>
                        <dl class="row mb-0">
                            <dt class="col-5 text-muted">Curso</dt>
                            <dd class="col-7">{{ $training->course->title }}</dd>

                            <dt class="col-5 text-muted">Instructor</dt>
                            <dd class="col-7">{{ $training->teacher->person->first_names ?? $training->teacher->name ?? 'Sin profesor' }}</dd>

                            <dt class="col-5 text-muted">Inicio</dt>
                            <dd class="col-7">{{ optional($training->start_date)->format('d/m/Y') ?? 'Sin fecha' }}</dd>

                            <dt class="col-5 text-muted">Fin</dt>
                            <dd class="col-7">{{ optional($training->end_date)->format('d/m/Y') ?? 'Sin fecha' }}</dd>

                            <dt class="col-5 text-muted">Modalidad</dt>
                            <dd class="col-7">{{ ucfirst($training->modality ?? 'No definida') }}</dd>

                            <dt class="col-5 text-muted">Estado</dt>
                            <dd class="col-7">
                                <span class="badge bg-{{ $training->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $training->status === 'active' ? 'Activo' : ucfirst($training->status ?? 'Inactivo') }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Resumen del curso</h5>
                        <p class="text-muted mb-3">{{ $training->course->description ?? 'No hay descripción disponible para este curso.' }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary">{{ $training->assessments->count() }} evaluaciones</span>
                            <span class="badge bg-info">{{ $training->enrollments->count() }} inscritos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Evaluaciones disponibles</h5>
                        <p class="text-muted small mb-0">Revisa las evaluaciones publicadas por el docente para esta capacitación.</p>
                    </div>
                </div>

                @if($training->assessments->isEmpty())
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="bi bi-inbox me-2"></i>Aún no hay evaluaciones creadas para esta capacitación.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($training->assessments as $assessment)
                            <div class="list-group-item list-group-item-action mb-2 rounded-3 shadow-sm">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                    <div>
                                        <h6 class="mb-1">{{ $assessment->title }}</h6>
                                        <p class="text-muted small mb-1">{{ $assessment->description ?? 'Sin descripción' }}</p>
                                        <small class="text-muted">
                                            Inicio: {{ optional($assessment->start_date)->format('d/m/Y') ?? 'Sin fecha' }} ·
                                            Fin: {{ optional($assessment->end_date)->format('d/m/Y') ?? 'Sin fecha' }} ·
                                            Intentos: {{ $assessment->allowed_attempts ?? 1 }}
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-{{ $assessment->active ? 'success' : 'secondary' }}">
                                            {{ $assessment->active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        @if($assessment->active)
                                            <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-square me-1"></i>Tomar examen
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Mi progreso en evaluaciones</h5>
                        <p class="text-muted small mb-0">Revisa tus intentos y resultados del curso.</p>
                    </div>
                </div>

                @if(isset($attempts) && $attempts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Evaluación</th>
                                    <th>Fecha</th>
                                    <th>Puntaje</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->assessment->title ?? 'Sin evaluación' }}</td>
                                        <td>{{ optional($attempt->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $attempt->score }}</td>
                                        <td>
                                            @if($attempt->score > 0)
                                                <span class="badge bg-success">Aprobado</span>
                                            @else
                                                <span class="badge bg-danger">Reprobado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0" role="alert">
                        No tienes intentos registrados para este curso aún.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
