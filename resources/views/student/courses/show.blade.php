@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $training->course->title }}</h1>
                <p class="text-muted mb-0">Capacitación con acceso a evaluaciones, tareas, asistencia, calificaciones y anuncios.</p>
            </div>
            <div class="text-end">
                <a href="{{ route('student.courses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle me-1"></i>Volver a mis cursos
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=inicio"
                            class="nav-link @if(request('tab', 'inicio') === 'inicio') active @endif" id="inicio-tab"
                            role="tab">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=estudiantes"
                            class="nav-link @if(request('tab') === 'estudiantes') active @endif" id="estudiantes-tab"
                            role="tab">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=asistencias"
                            class="nav-link @if(request('tab') === 'asistencias') active @endif" id="asistencias-tab"
                            role="tab">
                            <i class="bi bi-clipboard-check me-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=contenido"
                            class="nav-link @if(request('tab') === 'contenido') active @endif" id="contenido-tab"
                            role="tab">
                            <i class="bi bi-book-fill me-2"></i>Contenido/Tareas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=anuncios"
                            class="nav-link @if(request('tab') === 'anuncios') active @endif" id="anuncios-tab"
                            role="tab">
                            <i class="bi bi-megaphone-fill me-2"></i>Anuncios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @if(request('tab', 'inicio') === 'inicio')
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-start border-primary border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-2">{{ $training->course->title }}</h5>
                                    <p class="text-muted small mb-2">Instructor: {{ $training->teacher->person->first_names ?? $training->teacher->name ?? 'Sin profesor' }}</p>
                                    <p class="text-muted small mb-0">{{ $training->course->description ?? 'Sin descripción del curso.' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-start border-success border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted small mb-2">Inicio: <strong>{{ optional($training->start_date)->format('d/m/Y') ?? 'Sin fecha' }}</strong></p>
                                    <p class="text-muted small mb-2">Fin: <strong>{{ optional($training->end_date)->format('d/m/Y') ?? 'Sin fecha' }}</strong></p>
                                    <p class="text-muted small mb-2">Modalidad: <strong>{{ ucfirst($training->modality ?? 'No definida') }}</strong></p>
                                    <p class="text-muted small mb-0">Evaluaciones: <strong>{{ $training->assessments->count() }}</strong> · Tareas: <strong>{{ $training->tasks->count() }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-book-half text-primary h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Evaluaciones</h5>
                                    <p class="text-muted mb-0">{{ $training->assessments->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock-history text-info h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Asistencias</h5>
                                    <p class="text-muted mb-0">{{ $attendances->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-megaphone text-success h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Anuncios</h5>
                                    <p class="text-muted mb-0">{{ $training->announcements->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'estudiantes')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Compañeros de curso</h5>
                    </div>
                    @if($training->enrollments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($training->enrollments as $enrollmentItem)
                                        <tr>
                                            <td class="fw-bold">{{ $enrollmentItem->student->person->first_names ?? 'Sin nombre' }} {{ $enrollmentItem->student->person->last_names ?? '' }}</td>
                                            <td>{{ $enrollmentItem->student->person->document_number ?? '-' }}</td>
                                            <td><small>{{ $enrollmentItem->student->person->email ?? '-' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay otros estudiantes registrados en este curso.
                        </div>
                    @endif

                @elseif(request('tab') === 'asistencias')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Historial de asistencia</h5>
                    </div>
                    @if($attendances->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Sesión</th>
                                        <th>Asistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{ optional($attendance->schedule->date)->format('d/m/Y') ?? 'Sin fecha' }}</td>
                                            <td>{{ $attendance->schedule->topic ?? 'Sesión' }}</td>
                                            <td>
                                                @php $status = $attendance->attendance_status ?? $attendance->attendance; @endphp
                                                @if($status === 'present')
                                                    <span class="badge bg-success">Presente</span>
                                                @elseif($status === 'absent')
                                                    <span class="badge bg-danger">Ausente</span>
                                                @elseif($status === 'late')
                                                    <span class="badge bg-warning text-dark">Tarde</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($status ?? 'Desconocido') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay registros de asistencia para ti en este curso.
                        </div>
                    @endif

                @elseif(request('tab') === 'contenido')
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0 fw-bold">Evaluaciones disponibles</h6>
                                </div>
                                <div class="card-body">
                                    @if($training->assessments->isEmpty())
                                        <div class="alert alert-info mb-0" role="alert">
                                            <i class="bi bi-inbox me-2"></i>No hay evaluaciones disponibles.
                                        </div>
                                    @else
                                        <div class="list-group">
                                            @foreach($training->assessments as $assessment)
                                                <div class="list-group-item mb-2 rounded-3 shadow-sm">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <div>
                                                            <h6 class="mb-1">{{ $assessment->title }}</h6>
                                                            <p class="text-muted small mb-1">{{ $assessment->description ?? 'Sin descripción' }}</p>
                                                            <small class="text-muted">
                                                                Inicio: {{ optional($assessment->start_date)->format('d/m/Y') ?? 'Sin fecha' }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') ?? 'Sin fecha' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge bg-{{ $assessment->active ? 'success' : 'secondary' }} mb-2">
                                                                {{ $assessment->active ? 'Activo' : 'Inactivo' }}
                                                            </span>
                                                            @if($assessment->active)
                                                                <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}" class="btn btn-sm btn-primary">
                                                                    Tomar examen
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
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0 fw-bold">Tareas del curso</h6>
                                </div>
                                <div class="card-body">
                                    @if($training->tasks->isEmpty())
                                        <div class="alert alert-info mb-0" role="alert">
                                            <i class="bi bi-inbox me-2"></i>No hay tareas registradas.
                                        </div>
                                    @else
                                        <div class="list-group">
                                            @foreach($training->tasks as $task)
                                                <div class="list-group-item mb-2 rounded-3 shadow-sm">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <div>
                                                            <h6 class="mb-1">{{ $task->title }}</h6>
                                                            <p class="text-muted small mb-1">{{ $task->description }}</p>
                                                            <small class="text-muted">Vence: {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</small>
                                                        </div>
                                                        <div>
                                                            @if($task->file_path)
                                                                <a href="{{ asset('storage/'.$task->file_path) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                                    <i class="bi bi-download me-1"></i>Archivo
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
                        </div>
                    </div>

                @elseif(request('tab') === 'calificaciones')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Mis calificaciones</h5>
                    </div>

                    @if($attempts->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light text-center small text-uppercase">
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
                                            <td class="text-center">{{ optional($attempt->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">{{ $attempt->score }}</td>
                                            <td class="text-center">
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
                            <i class="bi bi-info-circle me-2"></i>No tienes calificaciones registradas todavía.
                        </div>
                    @endif

                @elseif(request('tab') === 'anuncios')
                    @if($training->announcements->isEmpty())
                        <div class="alert alert-secondary mb-0" role="alert">
                            <i class="bi bi-inbox me-2"></i>No hay anuncios publicados para este curso.
                        </div>
                    @else
                        @foreach($training->announcements->sortByDesc('created_at') as $announcement)
                            <div class="border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">Anuncio</h6>
                                        <small class="text-muted">{{ $announcement->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="mb-3 text-break">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>
                                @if($announcement->link)
                                    <div class="mb-3">
                                        <a href="{{ $announcement->link }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-link-45deg me-1"></i>{{ $announcement->link }}
                                        </a>
                                    </div>
                                @endif
                                @if($announcement->attachments)
                                    <div class="row g-2">
                                        @foreach($announcement->attachments as $attachment)
                                            <div class="col-12 col-sm-6 col-xl-4">
                                                <div class="card border">
                                                    <div class="card-body p-2">
                                                        @if(strpos($attachment['mime'] ?? '', 'image') !== false)
                                                            <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank">
                                                                <img src="{{ asset('storage/'.$attachment['path']) }}" class="img-fluid rounded mb-2" alt="{{ $attachment['name'] }}">
                                                            </a>
                                                        @endif
                                                        <div class="small mb-2">
                                                            <strong>{{ $attachment['name'] }}</strong>
                                                        </div>
                                                        <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank" class="text-decoration-none small">
                                                            <i class="bi bi-download me-1"></i>Descargar archivo
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
    <style>
        .course-banner-image { background-position: center center; background-size: cover; width: 100%; }
        .course-banner-fallback { background: #0d6efd; }
        .course-banner-overlay { position: absolute; inset: 0; pointer-events: none; }
    </style>
@endsection
