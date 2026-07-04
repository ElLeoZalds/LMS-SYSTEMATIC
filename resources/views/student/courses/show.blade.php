@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h1>
                <p class="text-muted mb-0">Capacitación con acceso a evaluaciones, tareas, asistencia, calificaciones y anuncios.</p>
            </div>
            <div class="text-end">
                <a href="{{ route('student.courses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle me-1"></i>Volver al Curso
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                            <i class="bi bi-book-fill me-2"></i>Evaluaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=tareas"
                            class="nav-link @if(request('tab') === 'tareas') active @endif" id="tareas-tab"
                            role="tab">
                            <i class="bi bi-list-task me-2"></i>Tareas
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
                                    <h5 class="card-title fw-bold text-dark mb-2">{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h5>
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

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-bold">Módulos y contenidos</h5>
                        </div>
                        <div class="card-body">
                            @if($modules->isNotEmpty())
                                @foreach($modules as $module)
                                    <div class="border rounded-3 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">{{ $module->title }}</h6>
                                                <p class="text-muted small mb-0">{{ $module->description ?: 'Explora los contenidos de este módulo.' }}</p>
                                            </div>
                                            <span class="badge bg-light text-dark">{{ $module->contents->count() }} contenidos</span>
                                        </div>
                                        @if($module->contents->isNotEmpty())
                                            <div class="list-group mt-3">
                                                @foreach($module->contents as $contentItem)
                                                    @php $isCompleted = in_array($contentItem->content_id, $completedContentIds ?? [], true); @endphp
                                                    <a href="{{ route('student.courses.view-content', ['training' => $training->training_id, 'content' => $contentItem->content_id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="me-2 {{ $isCompleted ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-secondary' }}"></i>
                                                            {{ $contentItem->title }}
                                                        </span>
                                                        <small class="text-muted text-uppercase">{{ $contentItem->type }}</small>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info small mb-0 mt-3" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>No hay contenidos registrados para este módulo todavía.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>No hay módulos disponibles para este curso.
                                </div>
                            @endif
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
                                                @elseif($status === 'justified')
                                                    <span class="badge bg-info text-dark">Justificado</span>
                                                @elseif($status === 'late')
                                                    <span class="badge bg-warning text-dark">Tardanza</span>
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

                @elseif(request('tab', 'contenido') === 'contenido')
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
                                        @php
                                            $assessmentAttempts = $attempts->where('assessment_id', $assessment->assessment_id);
                                            $pendingAttempt = $assessmentAttempts->first(function ($attempt) {
                                                return is_null($attempt->submitted_at);
                                            });
                                            $attemptsUsed = $assessmentAttempts->count();
                                            $remainingAttempts = max(0, $assessment->allowed_attempts - $attemptsUsed);
                                            $assessmentAvailable = $assessment->active
                                                && (! $assessment->start_date || \Carbon\Carbon::today()->gte(\Carbon\Carbon::parse($assessment->start_date)->startOfDay()))
                                                && (! $assessment->end_date || \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($assessment->end_date)->endOfDay()))
                                                && ! $assessment->training->isClosed();
                                        @endphp
                                        <div class="list-group-item mb-2 rounded-3 shadow-sm">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                <div>
                                                    <h6 class="mb-1">{{ $assessment->title }}</h6>
                                                    <p class="text-muted small mb-1">{{ $assessment->description ?? 'Sin descripción' }}</p>
                                                    <small class="text-muted">
                                                        Inicio: {{ optional($assessment->start_date)->format('d/m/Y') ?? 'Sin fecha' }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') ?? 'Sin fecha' }}
                                                    </small>
                                                    <p class="text-muted small mb-0 mt-1">
                                                        Intentos usados: {{ $attemptsUsed }} / {{ $assessment->allowed_attempts }}
                                                        @if($pendingAttempt)
                                                            · Examen en curso
                                                        @elseif($remainingAttempts <= 0)
                                                            · Intentos agotados
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $assessment->active ? 'success' : 'secondary' }} mb-2">
                                                        {{ $assessment->active ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                    @if($assessmentAvailable && $pendingAttempt)
                                                        <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" data-start-url="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" class="btn btn-sm btn-primary exam-start-btn">
                                                            Continuar examen
                                                        </a>
                                                    @elseif($assessmentAvailable && $remainingAttempts > 0)
                                                        <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" data-start-url="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" class="btn btn-sm btn-primary exam-start-btn">
                                                            Tomar examen
                                                        </a>
                                                    @elseif($assessmentAvailable)
                                                        <span class="badge bg-danger">Intentos agotados</span>
                                                    @elseif(! $assessmentAvailable && $assessment->active)
                                                        <span class="badge bg-secondary">No disponible</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif(request('tab') === 'tareas')
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
                                        @php
                                            $submission = $submissions->get($task->task_id);
                                            $taskDueClass = $task->due_date && $task->due_date->isPast() ? 'danger' : 'warning';
                                        @endphp
                                        <div id="task-{{ $task->task_id }}" class="list-group-item mb-2 rounded-3 shadow-sm">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                <div>
                                                    <h6 class="mb-1">{{ $task->title }}</h6>
                                                    <p class="text-muted small mb-1">{{ $task->description }}</p>
                                                    <small class="text-muted d-block mb-2">Vence: {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</small>
                                                    @if($submission)
                                                        <div class="small mb-1">
                                                            <span class="badge bg-success">Entregado</span>
                                                            @if($submission->grade !== null)
                                                                <span class="badge bg-primary">Calificación: {{ $submission->grade }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="small text-muted mb-1">Última entrega: {{ optional($submission->submitted_at)->format('d/m/Y H:i') }}</div>
                                                    @else
                                                        <span class="badge bg-{{ $taskDueClass }} text-dark">{{ $task->due_date && $task->due_date->isPast() ? 'Atrasada' : 'Pendiente' }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    @if($task->file_path)
                                                        <a href="{{ asset('storage/'.$task->file_path) }}" class="btn btn-sm btn-outline-secondary mb-2" target="_blank">
                                                            <i class="bi bi-download me-1"></i>Archivo
                                                        </a>
                                                    @endif
                                                    @if($submission && $submission->file_path)
                                                        <a href="{{ asset('storage/'.$submission->file_path) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                                            <i class="bi bi-cloud-download me-1"></i>Mi entrega
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($submission && $submission->teacher_feedback)
                                                <div class="mt-2 small text-muted">Feedback del profesor: {{ $submission->teacher_feedback }}</div>
                                            @endif

                                            <form action="{{ route('student.tasks.submit', $task->task_id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small fw-semibold">Comentarios de la entrega</label>
                                                    <textarea name="submission_text" rows="3" class="form-control form-control-sm">{{ old('submission_text', $submission->submission_text ?? '') }}</textarea>
                                                </div>
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-sm-8">
                                                        <label class="form-label small fw-semibold">Archivo adjunto (opcional)</label>
                                                        <input type="file" name="attachment" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                                                    </div>
                                                    <div class="col-sm-4 text-sm-end">
                                                        <button class="btn btn-sm btn-{{ $submission ? 'outline-primary' : 'primary' }}" type="submit">
                                                            {{ $submission ? 'Actualizar entrega' : 'Entregar tarea' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif(request('tab') === 'calificaciones')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Mi progreso por módulos</h5>
                    </div>

                    <div class="card border-0 shadow-sm rounded-lg mb-4" style="background-color: @if($generalAverage >= 13) #d1e7dd @else #fff3cd @endif; border-left: 5px solid @if($generalAverage >= 13) #198754 @else #ffc107 @endif; color: @if($generalAverage >= 13) #0f5132 @else #664d03 @endif;">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        @if($generalAverage >= 13)
                                            <span class="badge badge-success me-2 px-2 py-1">APROBADO</span>
                                            <h5 class="fw-bold mb-0 text-success" style="display: inline-block; vertical-align: middle;">¡Curso Aprobado!</h5>
                                        @else
                                            <span class="badge badge-warning text-dark me-2 px-2 py-1">EN PROGRESO</span>
                                            <h5 class="fw-bold mb-0 text-warning" style="display: inline-block; vertical-align: middle; color: #664d03 !important;">Progreso en curso</h5>
                                        @endif
                                    </div>
                                    <p class="mb-0 text-dark">
                                        Tu promedio general del curso es <strong>{{ $generalAverage }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($moduleReports)
                        <div class="accordion" id="moduleGradesAccordion">
                            @foreach($moduleReports as $index => $report)
                                <div class="accordion-item shadow-sm border-0 mb-3 rounded-3 overflow-hidden">
                                    <h2 class="accordion-header" id="module-heading-{{ $index }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#module-collapse-{{ $index }}" aria-expanded="false" aria-controls="module-collapse-{{ $index }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <span class="fw-bold text-dark">{{ $report['module']->title }}</span>
                                                <span class="badge {{ is_null($report['average']) ? 'bg-secondary' : ($report['average'] >= 11 ? 'bg-success' : 'bg-warning text-dark') }}">
                                                    Promedio del módulo: {{ is_null($report['average']) ? '-' : $report['average'] }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="module-collapse-{{ $index }}" class="accordion-collapse collapse" aria-labelledby="module-heading-{{ $index }}" data-bs-parent="#moduleGradesAccordion">
                                        <div class="accordion-body">
                                            @if($report['items'])
                                                <div class="list-group">
                                                    @foreach($report['items'] as $item)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-semibold">{{ $item['title'] }}</div>
                                                                <small class="text-muted">{{ $item['type'] === 'task' ? 'Tarea' : 'Evaluación' }}</small>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="fw-bold">
                                                                    {{ is_null($item['grade']) ? '-' : $item['grade'] }}
                                                                </div>
                                                                <span class="badge {{ $item['state'] === 'Calificado' ? 'bg-success' : ($item['state'] === 'Entregado' ? 'bg-info' : 'bg-secondary') }}">
                                                                    {{ $item['state'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0" role="alert">
                                                    <i class="bi bi-info-circle me-2"></i>No hay actividades registradas para este módulo.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.exam-start-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const startUrl = button.dataset.startUrl || button.href;

                    Swal.fire({
                        title: '¿Estás listo para iniciar el examen?',
                        text: 'El tiempo comenzará cuando confirmes. Asegúrate de estar preparado.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, comenzar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = startUrl;
                        }
                    });
                });
            });
        });
    </script>
@endsection
